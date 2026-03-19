<?php
require_once 'app/models/BaseModel.php';

class TheLoaiModel extends BaseModel {
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM the_loai ORDER BY TenTheLoai ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
