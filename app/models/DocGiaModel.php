<?php
require_once 'app/models/BaseModel.php';

class DocGiaModel extends BaseModel {
    
    public function getAll($search = '') {
        // ENTERPRISE SYNC: Join with Membership Tiers (Hang Thanh Vien)
        $sql = "SELECT dg.*, tk.TenDangNhap, tk.TrangThai as TinhTrangTK, htv.TenHang, htv.SoSachToiDa
                FROM doc_gia dg 
                LEFT JOIN tai_khoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan
                LEFT JOIN hang_thanh_vien htv ON dg.MaHang = htv.MaHang";
        
        if (!empty($search)) {
            $sql .= " WHERE dg.HoTen LIKE :search OR dg.Email LIKE :search OR dg.DienThoai LIKE :search OR tk.TenDangNhap LIKE :search";
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
        $sql = "SELECT dg.*, tk.TenDangNhap, tk.TrangThai as TinhTrangTK, htv.TenHang, htv.SoSachToiDa
                FROM doc_gia dg 
                LEFT JOIN tai_khoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan
                LEFT JOIN hang_thanh_vien htv ON dg.MaHang = htv.MaHang
                WHERE dg.MaDocGia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // ENTERPRISE: Default to Basic Membership (MaHang = 1) if not set
        $maHang = $data['MaHang'] ?? 1;
        $stmt = $this->db->prepare("INSERT INTO doc_gia (HoTen, NgaySinh, Email, DienThoai, DiaChi, TrangThai, MaHang) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['HoTen'], $data['NgaySinh'], $data['Email'], $data['DienThoai'], $data['DiaChi'], $data['TrangThai'] ?? 'ACTIVE', $maHang
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE doc_gia SET HoTen=?, NgaySinh=?, Email=?, DienThoai=?, DiaChi=?, TrangThai=?, MaHang=? WHERE MaDocGia=?");
        return $stmt->execute([
            $data['HoTen'], $data['NgaySinh'], $data['Email'], $data['DienThoai'], $data['DiaChi'], $data['TrangThai'], $data['MaHang'] ?? 1, $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM doc_gia WHERE MaDocGia = ?");
        return $stmt->execute([$id]);
    }

    public function getHangThanhVien() {
        return $this->db->query("SELECT * FROM hang_thanh_vien")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * VIP FEATURE: IDENTITY DISCOVERY (Map Account to Reader)
     */
    public function getByTaiKhoan($maTaiKhoan) {
        $stmt = $this->db->prepare("SELECT * FROM doc_gia WHERE MaTaiKhoan = ?");
        $stmt->execute([$maTaiKhoan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
