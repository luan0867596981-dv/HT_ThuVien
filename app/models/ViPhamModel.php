<?php
require_once 'app/models/BaseModel.php';

class ViPhamModel extends BaseModel {
    
    public function getAll($search = '') {
        $sql = "SELECT vp.*, dg.HoTen, dg.DienThoai, dg.Email 
                FROM vi_pham vp 
                JOIN doc_gia dg ON vp.MaDocGia = dg.MaDocGia";
        if (!empty($search)) {
            $sql .= " WHERE dg.HoTen LIKE :search OR dg.DienThoai LIKE :search OR vp.MaViPham LIKE :search";
        }
        $sql .= " ORDER BY vp.TrangThaiThanhToan ASC, vp.MaViPham DESC";

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function thanhToan($maViPham) {
        $stmt = $this->db->prepare("UPDATE vi_pham SET TrangThaiThanhToan = 'DA_THANH_TOAN' WHERE MaViPham = ?");
        return $stmt->execute([$maViPham]);
    }
}
?>
