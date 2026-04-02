<?php
require_once 'app/models/BaseModel.php';

class SachModel extends BaseModel {
    
    public function getAll($search = '') {
        // ENTERPRISE SYNC: Join dau_sach with aggregate cuon_sach count and representative position
        $sql = "SELECT ds.*, tg.TenTacGia, tl.TenTheLoai, 
                (SELECT COUNT(*) FROM cuon_sach cs WHERE cs.MaDauSach = ds.MaDauSach AND cs.TrangThaiHienTai = 'AVAILABLE') as SoLuong,
                (SELECT ViTriKe FROM cuon_sach cs WHERE cs.MaDauSach = ds.MaDauSach LIMIT 1) as ViTriKe
                FROM dau_sach ds 
                LEFT JOIN tac_gia tg ON ds.MaTacGia = tg.MaTacGia 
                LEFT JOIN the_loai tl ON ds.MaTheLoai = tl.MaTheLoai";
        
        if (!empty($search)) {
            $sql .= " WHERE ds.TenSach LIKE :search OR tg.TenTacGia LIKE :search OR tl.TenTheLoai LIKE :search OR ds.ISBN LIKE :search";
        }
        $sql .= " ORDER BY ds.MaDauSach ASC";
        
        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT ds.*, tg.TenTacGia, tl.TenTheLoai,
                (SELECT COUNT(*) FROM cuon_sach cs WHERE cs.MaDauSach = ds.MaDauSach AND cs.TrangThaiHienTai = 'AVAILABLE') as SoLuong,
                (SELECT ViTriKe FROM cuon_sach cs WHERE cs.MaDauSach = ds.MaDauSach LIMIT 1) as ViTriKe
                FROM dau_sach ds 
                LEFT JOIN tac_gia tg ON ds.MaTacGia = tg.MaTacGia 
                LEFT JOIN the_loai tl ON ds.MaTheLoai = tl.MaTheLoai 
                WHERE ds.MaDauSach = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO dau_sach (TenSach, MaTacGia, MaTheLoai, NhaXuatBan, NamXuatBan, ISBN, MoTa, AnhBia) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenSach'], $data['MaTacGia'], $data['MaTheLoai'], $data['NhaXuatBan'], 
            $data['NamXuatBan'], $data['ISBN'], $data['MoTa'], $data['AnhBia']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE dau_sach SET TenSach=?, MaTacGia=?, MaTheLoai=?, NhaXuatBan=?, NamXuatBan=?, 
                ISBN=?, MoTa=?, AnhBia=? WHERE MaDauSach=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenSach'], $data['MaTacGia'], $data['MaTheLoai'], $data['NhaXuatBan'], 
            $data['NamXuatBan'], $data['ISBN'], $data['MoTa'], $data['AnhBia'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM dau_sach WHERE MaDauSach = ?");
        return $stmt->execute([$id]);
    }
}
?>
