<?php
require_once 'app/models/BaseModel.php';

class ThongKeModel extends BaseModel {
    
    public function getTongSach() {
        // Enterprise Logic: Đếm số lượng cuốn sách vật lý thực tế trong kho
        $stmt = $this->db->query("SELECT COUNT(*) as Tong FROM cuon_sach");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }

    public function getTongDocGia() {
        // Enterprise Logic: Đếm số lượng độc giả đang hoạt động
        $stmt = $this->db->query("SELECT COUNT(*) as Tong FROM doc_gia");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }

    public function getTongDangMuon() {
        // Enterprise Logic: Đếm số lượng phiếu mượn đang trong trạng thái lưu thông
        $stmt = $this->db->query("SELECT COUNT(*) as Tong FROM phieu_muon WHERE TrangThai = 'BORROWING'");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }

    public function getTongQuaHan() {
        // Enterprise Logic: Đếm số phiếu mượn bị trễ hạn hoặc trạng thái LATE
        $stmt = $this->db->query("SELECT COUNT(*) as Tong FROM phieu_muon WHERE TrangThai = 'LATE' OR (TrangThai = 'BORROWING' AND HanTra < NOW())");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['Tong'] ?? 0;
    }
}
?>
