<?php
require_once 'app/models/BaseModel.php';

class MuonSachModel extends BaseModel {
    
    public function getAll($search = '') {
        $sql = "SELECT pm.*, dg.HoTen, dg.DienThoai, cn.TenChiNhanh
                FROM phieu_muon pm 
                JOIN doc_gia dg ON pm.MaDocGia = dg.MaDocGia 
                LEFT JOIN chi_nhanh cn ON pm.MaChiNhanh = cn.MaChiNhanh ";
        if (!empty($search)) {
            $sql .= " WHERE dg.HoTen LIKE :search OR pm.MaPhieuMuon LIKE :search OR pm.MaChiNhanh LIKE :search ";
        }
        $sql .= " ORDER BY pm.MaPhieuMuon DESC";

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChiTietPhieu($maPhieuMuon) {
        $stmt = $this->db->prepare("SELECT ct.*, ds.TenSach, ds.AnhBia, cs.MaVach 
                FROM chi_tiet_muon ct 
                JOIN cuon_sach cs ON ct.MaCuonSach = cs.MaCuonSach
                JOIN dau_sach ds ON cs.MaDauSach = ds.MaDauSach 
                WHERE ct.MaPhieuMuon = ?");
        $stmt->execute([$maPhieuMuon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lapPhieuMuon($data) {
        try {
            // TASK 2: STRICT VIOLATION VALIDATION (Check Late Loans OR Banned Account)
            $stmtCheck = $this->db->prepare("SELECT 
                (SELECT COUNT(*) FROM phieu_muon WHERE MaDocGia = ? AND (TrangThai = 'LATE' OR (TrangThai = 'BORROWING' AND HanTra < NOW()))) + 
                (SELECT COUNT(*) FROM doc_gia WHERE MaDocGia = ? AND TrangThai = 'BANNED')
            ");
            $stmtCheck->execute([$data['MaDocGia'], $data['MaDocGia']]);
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("ACCOUNT_BLOCKED_VIOLATION");
            }

            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO phieu_muon (MaDocGia, MaNhanVien, MaChiNhanh, NgayMuon, HanTra, TrangThai) VALUES (?, ?, ?, NOW(), ?, 'BORROWING')");
            $stmt->execute([
                $data['MaDocGia'], 
                $data['MaNhanVien'] ?? $_SESSION['user']['MaTaiKhoan'], 
                $data['MaChiNhanh'] ?? 1, 
                $data['HanTra']
            ]);
            $maPhieuMuon = $this->db->lastInsertId();

            $stmtCT = $this->db->prepare("INSERT INTO chi_tiet_muon (MaPhieuMuon, MaCuonSach) VALUES (?, ?)");
            $stmtBook = $this->db->prepare("UPDATE cuon_sach SET TrangThaiHienTai = 'BORROWED' WHERE MaCuonSach = ? AND TrangThaiHienTai = 'AVAILABLE'");

            foreach ($data['sachList'] as $maCuonSach) {
                if(empty($maCuonSach)) continue;
                $stmtCT->execute([$maPhieuMuon, $maCuonSach]);
                
                $stmtBook->execute([$maCuonSach]);
                if ($stmtBook->rowCount() == 0) {
                    throw new Exception("BOOK_NOT_AVAILABLE");
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e; // Rethrow to handle in controller
        }
    }

    /**
     * VIP FEATURE: ĐĂNG KÝ MƯỢN / ĐẶT TRƯỚC (Pre-order Title)
     */
    public function datTruocDauSach($maDauSach, $maDocGia) {
        // Kiểm tra độc giả có đang bị khóa hay nợ sách không (Reuse validation)
        $stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM doc_gia WHERE MaDocGia = ? AND TrangThai = 'BANNED'");
        $stmtCheck->execute([$maDocGia]);
        if ($stmtCheck->fetchColumn() > 0) {
            return "ACCOUNT_BANNED";
        }

        // Tạo phiếu mượn với trạng thái REQUESTED
        // MaChiNhanh mac dinh la chi nhanh 1 (Hanoi Central) neu khong co cuon sach cu the
        $sql = "INSERT INTO phieu_muon (MaDocGia, MaDauSach, MaChiNhanh, NgayMuon, HanTra, TrangThai) 
                VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 'REQUESTED')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$maDocGia, $maDauSach, 1]);
    }

    /**
     * STAFF FEATURE: LẤY DANH SÁCH PHIẾU CHỜ DUYỆT
     */
    public function getPhieuChoDuyet() {
        $sql = "SELECT pm.*, dg.HoTen, ds.TenSach, ds.AnhBia 
                FROM phieu_muon pm 
                JOIN doc_gia dg ON pm.MaDocGia = dg.MaDocGia 
                JOIN dau_sach ds ON pm.MaDauSach = ds.MaDauSach 
                WHERE pm.TrangThai = 'REQUESTED' 
                ORDER BY pm.NgayMuon ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * STAFF FEATURE: PHÊ DUYỆT MƯỢN (Assign physical Book)
     */
    public function duyetPhieuMuon($maPhieuMuon, $maCuonSach, $maNhanVien) {
        try {
            $this->db->beginTransaction();

            // 1. Cap nhat trang thai phieu muon
            $stmt = $this->db->prepare("UPDATE phieu_muon SET TrangThai = 'BORROWING', MaNhanVien = ? WHERE MaPhieuMuon = ?");
            $stmt->execute([$maNhanVien, $maPhieuMuon]);

            // 2. Them vao chi tiet muon (Xac dinh cuon sach thuc te)
            $stmtCT = $this->db->prepare("INSERT INTO chi_tiet_muon (MaPhieuMuon, MaCuonSach) VALUES (?, ?)");
            $stmtCT->execute([$maPhieuMuon, $maCuonSach]);

            // 3. Cap nhat trang thai cuon sach
            $stmtBook = $this->db->prepare("UPDATE cuon_sach SET TrangThaiHienTai = 'BORROWED' WHERE MaCuonSach = ?");
            $stmtBook->execute([$maCuonSach]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function tuChoiPhieuMuon($maPhieuMuon) {
        $stmt = $this->db->prepare("UPDATE phieu_muon SET TrangThai = 'REJECTED' WHERE MaPhieuMuon = ?");
        return $stmt->execute([$maPhieuMuon]);
    }

    /**
     * READER FEATURE: XEM LỊCH SỬ MƯỢN CỦA TÔI
     */
    public function getPhieuByDocGia($maDocGia) {
        $sql = "SELECT pm.*, ds.TenSach, ds.AnhBia, 
                (SELECT GROUP_CONCAT(cs.MaVach) FROM chi_tiet_muon ctm JOIN cuon_sach cs ON ctm.MaCuonSach = cs.MaCuonSach WHERE ctm.MaPhieuMuon = pm.MaPhieuMuon) as MaVachList 
                FROM phieu_muon pm 
                LEFT JOIN dau_sach ds ON pm.MaDauSach = ds.MaDauSach 
                WHERE pm.MaDocGia = ? 
                ORDER BY pm.NgayMuon DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$maDocGia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
