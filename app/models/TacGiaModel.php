<?php
require_once 'app/models/BaseModel.php';

class TacGiaModel extends BaseModel {
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM tac_gia ORDER BY TenTacGia ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
