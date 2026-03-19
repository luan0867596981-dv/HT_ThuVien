<?php
require_once 'app/models/BaseModel.php';

class TraSachModel extends BaseModel {
    
    public function getPhieuTraHienTai() {
        $stmt = $this->db->query("SELECT pt.*, pm.MaDocGia, dg.HoTen, pm.NgayMuon 
                FROM phieu_tra pt 
                JOIN phieu_muon pm ON pt.MaPhieuMuon = pm.MaPhieuMuon 
                JOIN doc_gia dg ON pm.MaDocGia = dg.MaDocGia 
                ORDER BY pt.MaPhieuTra DESC LIMIT 100");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function xacNhanTra($maPhieuMuon, $tinhTrangSach, $tienPhat) {
        try {
            $this->db->beginTransaction();

            $stmtPM = $this->db->prepare("SELECT * FROM phieu_muon WHERE MaPhieuMuon = ?");
            $stmtPM->execute([$maPhieuMuon]);
            $pm = $stmtPM->fetch(PDO::FETCH_ASSOC);
            
            if (!$pm || $pm['TrangThai'] == 'DA_TRA') {
                throw new Exception("Phiếu mượn không tồn tại hoặc đã được trả.");
            }

            // Update status
            $trangThaiPhieuPM = (strtotime(date('Y-m-d')) > strtotime($pm['HanTra'])) ? 'QUA_HAN' : 'DA_TRA';
            if ($tinhTrangSach != 'HOP_LE') {
                $trangThaiPhieuPM = 'DA_TRA'; // Still returned, but damaged
            }
            
            $stmtUpdatePM = $this->db->prepare("UPDATE phieu_muon SET TrangThai = 'DA_TRA' WHERE MaPhieuMuon = ?");
            $stmtUpdatePM->execute([$maPhieuMuon]);

            $loaiViPham = null;
            if ($tienPhat > 0) {
                if (strtotime(date('Y-m-d')) > strtotime($pm['HanTra'])) {
                    $loaiViPham = 'TRE_HAN';
                }
                if ($tinhTrangSach == 'HU_HONG' || $tinhTrangSach == 'MAT_SACH') {
                    $loaiViPham = $tinhTrangSach;
                }
                
                if ($loaiViPham) {
                    $stmtVP = $this->db->prepare("INSERT INTO vi_pham (MaDocGia, LoaiViPham, TienPhat) VALUES (?, ?, ?)");
                    $stmtVP->execute([$pm['MaDocGia'], $loaiViPham, $tienPhat]);
                }
            }

            // Return books to inventory IF NOT LOST
            if ($tinhTrangSach != 'MAT_SACH') {
                $stmtGetSach = $this->db->prepare("SELECT MaSach, SoLuong FROM chi_tiet_muon WHERE MaPhieuMuon = ?");
                $stmtGetSach->execute([$maPhieuMuon]);
                $sachMuon = $stmtGetSach->fetchAll(PDO::FETCH_ASSOC);

                $stmtHoanSach = $this->db->prepare("UPDATE sach SET SoLuong = SoLuong + ? WHERE MaSach = ?");
                foreach ($sachMuon as $s) {
                    $stmtHoanSach->execute([$s['SoLuong'], $s['MaSach']]);
                }
            }

            // Create return ticket
            $trangThaiPT = ($loaiViPham) ? $loaiViPham : 'HOP_LE';
            $stmtPT = $this->db->prepare("INSERT INTO phieu_tra (MaPhieuMuon, TrangThai) VALUES (?, ?)");
            $stmtPT->execute([$maPhieuMuon, $trangThaiPT]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>
