<?php
require_once 'app/models/BaseModel.php';

class ThongKeModel extends BaseModel {
    
    public function getTongSach() {
        $stmt = $this->db->query("SELECT SUM(SoLuong) as Tong FROM sach");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }

    public function getTongDocGia() {
        $stmt = $this->db->query("SELECT COUNT(*) as Tong FROM doc_gia WHERE TrangThai = 'ACTIVE'");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }

    public function getTongDangMuon() {
        $stmt = $this->db->query("SELECT SUM(ct.SoLuong) as Tong 
                                  FROM chi_tiet_muon ct 
                                  JOIN phieu_muon pm ON ct.MaPhieuMuon = pm.MaPhieuMuon 
                                  WHERE pm.TrangThai = 'DANG_MUON'");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }

    public function getTongQuaHan() {
        $stmt = $this->db->query("SELECT COUNT(*) as Tong FROM phieu_muon WHERE TrangThai = 'QUA_HAN' OR (TrangThai = 'DANG_MUON' AND HanTra < NOW())");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }
}
?>
