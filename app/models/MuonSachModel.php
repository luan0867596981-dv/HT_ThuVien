<?php
require_once 'app/models/BaseModel.php';

class MuonSachModel extends BaseModel {
    
    public function getAll($search = '') {
        $sql = "SELECT pm.*, dg.HoTen, dg.DienThoai 
                FROM phieu_muon pm 
                JOIN doc_gia dg ON pm.MaDocGia = dg.MaDocGia ";
        if (!empty($search)) {
            $sql .= " WHERE dg.HoTen LIKE :search OR pm.MaPhieuMuon LIKE :search ";
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
        $stmt = $this->db->prepare("SELECT ct.*, s.TenSach, s.AnhBia 
                FROM chi_tiet_muon ct 
                JOIN sach s ON ct.MaSach = s.MaSach 
                WHERE ct.MaPhieuMuon = ?");
        $stmt->execute([$maPhieuMuon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lapPhieuMuon($maDocGia, $hanTra, $sachList) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO phieu_muon (MaDocGia, HanTra, TrangThai) VALUES (?, ?, 'DANG_MUON')");
            $stmt->execute([$maDocGia, $hanTra]);
            $maPhieuMuon = $this->db->lastInsertId();

            $stmtCT = $this->db->prepare("INSERT INTO chi_tiet_muon (MaPhieuMuon, MaSach, SoLuong) VALUES (?, ?, 1)");
            $stmtSach = $this->db->prepare("UPDATE sach SET SoLuong = SoLuong - 1 WHERE MaSach = ? AND SoLuong > 0");

            foreach ($sachList as $maSach) {
                if(empty($maSach)) continue;
                $stmtCT->execute([$maPhieuMuon, $maSach]);
                
                $stmtSach->execute([$maSach]);
                if ($stmtSach->rowCount() == 0) {
                    throw new Exception("Sách Hết Hàng: " . $maSach);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>
