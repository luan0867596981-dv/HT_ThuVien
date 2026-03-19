<?php
require_once 'app/models/BaseModel.php';

class DocGiaModel extends BaseModel {
    public function getAll($search = '') {
        $sql = "SELECT dg.*, tk.TenDangNhap, tk.TrangThai as TinhTrangTK 
                FROM doc_gia dg 
                LEFT JOIN tai_khoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan";
        if (!empty($search)) {
            $sql .= " WHERE dg.HoTen LIKE :search OR dg.Email LIKE :search OR dg.DienThoai LIKE :search";
        }
        $sql .= " ORDER BY dg.MaDocGia ASC";

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT dg.*, tk.TenDangNhap, tk.TrangThai as TinhTrangTK FROM doc_gia dg LEFT JOIN tai_khoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan WHERE dg.MaDocGia = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Assume user is added without TaiKhoan for simplity, or you could create both.
        // We'll just create a direct doc_gia for people who don't login but just go to library.
        $stmt = $this->db->prepare("INSERT INTO doc_gia (HoTen, NgaySinh, Email, DienThoai, DiaChi, TrangThai) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['HoTen'], $data['NgaySinh'], $data['Email'], $data['DienThoai'], $data['DiaChi'], $data['TrangThai']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE doc_gia SET HoTen=?, NgaySinh=?, Email=?, DienThoai=?, DiaChi=?, TrangThai=? WHERE MaDocGia=?");
        return $stmt->execute([
            $data['HoTen'], $data['NgaySinh'], $data['Email'], $data['DienThoai'], $data['DiaChi'], $data['TrangThai'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM doc_gia WHERE MaDocGia = ?");
        return $stmt->execute([$id]);
    }
}
?>
