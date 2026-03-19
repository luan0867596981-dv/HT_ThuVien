<?php
require_once 'app/models/BaseModel.php';

class SachModel extends BaseModel {
    public function getAll($search = '') {
        $sql = "SELECT s.*, tg.TenTacGia, tl.TenTheLoai 
                FROM sach s 
                LEFT JOIN tac_gia tg ON s.MaTacGia = tg.MaTacGia 
                LEFT JOIN the_loai tl ON s.MaTheLoai = tl.MaTheLoai";
        
        if (!empty($search)) {
            $sql .= " WHERE s.TenSach LIKE :search OR tg.TenTacGia LIKE :search OR tl.TenTheLoai LIKE :search";
        }
        $sql .= " ORDER BY s.MaSach ASC";
        
        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT s.*, tg.TenTacGia, tl.TenTheLoai 
                FROM sach s 
                LEFT JOIN tac_gia tg ON s.MaTacGia = tg.MaTacGia 
                LEFT JOIN the_loai tl ON s.MaTheLoai = tl.MaTheLoai 
                WHERE s.MaSach = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO sach (TenSach, MaTacGia, MaTheLoai, NhaXuatBan, NamXuatBan, ISBN, SoLuong, ViTriKe, MoTa, AnhBia) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenSach'], $data['MaTacGia'], $data['MaTheLoai'], $data['NhaXuatBan'], 
            $data['NamXuatBan'], $data['ISBN'], $data['SoLuong'], $data['ViTriKe'], 
            $data['MoTa'], $data['AnhBia']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE sach SET TenSach=?, MaTacGia=?, MaTheLoai=?, NhaXuatBan=?, NamXuatBan=?, 
                ISBN=?, SoLuong=?, ViTriKe=?, MoTa=?, AnhBia=? WHERE MaSach=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['TenSach'], $data['MaTacGia'], $data['MaTheLoai'], $data['NhaXuatBan'], 
            $data['NamXuatBan'], $data['ISBN'], $data['SoLuong'], $data['ViTriKe'], 
            $data['MoTa'], $data['AnhBia'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM sach WHERE MaSach = ?");
        return $stmt->execute([$id]);
    }
}
?>
