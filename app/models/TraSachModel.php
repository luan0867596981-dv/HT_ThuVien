<?php
require_once 'app/models/BaseModel.php';

class TraSachModel extends BaseModel {
    
    public function getPhieuTraHienTai() {
        // ENTERPRISE: Get recently returned items from chi_tiet_muon
        $stmt = $this->db->query("SELECT ct.*, pm.MaDocGia, dg.HoTen, pm.NgayMuon, ds.TenSach, cs.MaVach
                FROM chi_tiet_muon ct 
                JOIN phieu_muon pm ON ct.MaPhieuMuon = pm.MaPhieuMuon 
                JOIN doc_gia dg ON pm.MaDocGia = dg.MaDocGia 
                JOIN cuon_sach cs ON ct.MaCuonSach = cs.MaCuonSach
                JOIN dau_sach ds ON cs.MaDauSach = ds.MaDauSach
                WHERE ct.NgayTra IS NOT NULL
                ORDER BY ct.NgayTra DESC LIMIT 100");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function xacNhanTra($maPhieuMuon, $tinhTrangKhiTra, $tienPhat = 0) {
        try {
            $this->db->beginTransaction();

            // 1. Get loan ticket info
            $stmt = $this->db->prepare("SELECT * FROM phieu_muon WHERE MaPhieuMuon = ?");
            $stmt->execute([$maPhieuMuon]);
            $pm = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pm || $pm['TrangThai'] == 'COMPLETED') {
                throw new Exception("Phiếu mượn không tồn tại hoặc đã được trả hoàn tất.");
            }

            // 2. Update all items in this ticket to returned state
            $stmtUpdateCT = $this->db->prepare("UPDATE chi_tiet_muon SET NgayTra = NOW(), TinhTrangKhiTra = ? WHERE MaPhieuMuon = ? AND NgayTra IS NULL");
            $stmtUpdateCT->execute([$tinhTrangKhiTra, $maPhieuMuon]);

            // 3. Update all physical books status
            $newBookStatus = ($tinhTrangKhiTra == 'LOST') ? 'LOST' : (($tinhTrangKhiTra == 'DAMAGED') ? 'DAMAGED' : 'GOOD');
            $availStatus = ($tinhTrangKhiTra == 'LOST') ? 'MAINTENANCE' : 'AVAILABLE';

            // Subquery to get all book items in this ticket
            $stmtBooks = $this->db->prepare("SELECT MaCuonSach FROM chi_tiet_muon WHERE MaPhieuMuon = ?");
            $stmtBooks->execute([$maPhieuMuon]);
            $items = $stmtBooks->fetchAll(PDO::FETCH_ASSOC);

            $stmtUpdateBook = $this->db->prepare("UPDATE cuon_sach SET TinhTrang = ?, TrangThaiHienTai = ? WHERE MaCuonSach = ?");
            foreach ($items as $item) {
                $stmtUpdateBook->execute([$newBookStatus, $availStatus, $item['MaCuonSach']]);
            }

            // 4. Handle Violation (Fine)
            if ($tienPhat > 0 || strtotime(date('Y-m-d')) > strtotime($pm['HanTra'])) {
                $loaiVP = (strtotime(date('Y-m-d')) > strtotime($pm['HanTra'])) ? 'LATE' : ($tinhTrangKhiTra == 'DAMAGED' ? 'DAMAGED' : 'LOST');
                
                $stmtVP = $this->db->prepare("INSERT INTO vi_pham (MaPhieuMuon, MaDocGia, LoaiViPham, SoTienPhat, TrangThai) VALUES (?, ?, ?, ?, 'UNPAID')");
                $stmtVP->execute([$maPhieuMuon, $pm['MaDocGia'], $loaiVP, $tienPhat]);
            }

            // 5. Complete the main ticket
            $stmtEndPM = $this->db->prepare("UPDATE phieu_muon SET TrangThai = 'COMPLETED' WHERE MaPhieuMuon = ?");
            $stmtEndPM->execute([$maPhieuMuon]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }
}
?>
