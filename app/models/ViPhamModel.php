<?php
require_once 'app/models/BaseModel.php';

class ViPhamModel extends BaseModel {
    
    public function getAll($search = '') {
        // ENTERPRISE SYNC: Map to libsaas_enterprise finance schema
        $sql = "SELECT vp.*, dg.HoTen, dg.DienThoai, dg.Email 
                FROM vi_pham vp 
                JOIN doc_gia dg ON vp.MaDocGia = dg.MaDocGia";
        
        if (!empty($search)) {
            $sql .= " WHERE dg.HoTen LIKE :search OR dg.DienThoai LIKE :search OR vp.MaViPham LIKE :search OR vp.NoiDung LIKE :search";
        }
        $sql .= " ORDER BY (vp.TrangThai = 'UNPAID') DESC, vp.MaViPham DESC";

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function thanhToan($maViPham) {
        // ENTERPRISE: Update to PAID status and log transaction placeholder
        $stmt = $this->db->prepare("UPDATE vi_pham SET TrangThai = 'PAID' WHERE MaViPham = ?");
        return $stmt->execute([$maViPham]);
    }

    public function khoaQuyenDocGia($maViPham) {
        // ENTERPRISE: Get MaDocGia from ViPham and update doc_gia status to BANNED
        $stmt = $this->db->prepare("UPDATE doc_gia dg 
                                    INNER JOIN vi_pham vp ON dg.MaDocGia = vp.MaDocGia 
                                    SET dg.TrangThai = 'BANNED' 
                                    WHERE vp.MaViPham = ?");
        return $stmt->execute([$maViPham]);
    }
}
?>
