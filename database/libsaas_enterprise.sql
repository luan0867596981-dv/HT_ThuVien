/*
LIBSAAS ENTERPRISE DATABASE SCHEMA v3.0
Architect: Senior Database Architect
Engine: InnoDB | Charset: utf8mb4_unicode_ci
*/

CREATE DATABASE IF NOT EXISTS libsaas_enterprise;
USE libsaas_enterprise;

-- ==========================================
-- 1. CAU TRUC CHI NHANH & TO CHUC (Branch Layer)
-- ==========================================

CREATE TABLE chi_nhanh (
    MaChiNhanh INT AUTO_INCREMENT PRIMARY KEY,
    TenChiNhanh VARCHAR(100) NOT NULL,
    DiaChi VARCHAR(255),
    DienThoai VARCHAR(20),
    Email VARCHAR(100),
    NgayKichHoat DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================
-- 2. PHAN QUYEN & TAI KHOAN (Auth Layer)
-- ==========================================

CREATE TABLE tai_khoan (
    MaTaiKhoan INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50) UNIQUE NOT NULL,
    MatKhau VARCHAR(255) NOT NULL,
    HoTen VARCHAR(100),
    Email VARCHAR(100),
    VaiTro ENUM('ADMIN', 'LIBRARIAN', 'USER') DEFAULT 'USER',
    MaChiNhanh INT, -- Admin co the null, Librarian bat buoc thuoc chi nhanh
    TrangThai ENUM('ACTIVE', 'LOCKED') DEFAULT 'ACTIVE',
    Created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaChiNhanh) REFERENCES chi_nhanh(MaChiNhanh) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ==========================================
-- 3. QUAN LY KHO SACH CHUYEN SÂU (Inventory Layer)
-- ==========================================

CREATE TABLE the_loai (
    MaTheLoai INT AUTO_INCREMENT PRIMARY KEY,
    TenTheLoai VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE tac_gia (
    MaTacGia INT AUTO_INCREMENT PRIMARY KEY,
    TenTacGia VARCHAR(100) NOT NULL,
    TieuSu TEXT
) ENGINE=InnoDB;

-- Dau Sach (Metadata/Titles)
CREATE TABLE dau_sach (
    MaDauSach INT AUTO_INCREMENT PRIMARY KEY,
    TenSach VARCHAR(255) NOT NULL,
    MaTacGia INT,
    MaTheLoai INT,
    ISBN VARCHAR(20) UNIQUE,
    NhaXuatBan VARCHAR(100),
    NamXuatBan INT,
    AnhBia VARCHAR(255),
    MoTa TEXT,
    FOREIGN KEY (MaTacGia) REFERENCES tac_gia(MaTacGia) ON DELETE RESTRICT,
    FOREIGN KEY (MaTheLoai) REFERENCES the_loai(MaTheLoai) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Cuon Sach (Physical Items/Assets)
CREATE TABLE cuon_sach (
    MaCuonSach INT AUTO_INCREMENT PRIMARY KEY,
    MaVach VARCHAR(50) UNIQUE NOT NULL, -- Barcode/RFID
    MaDauSach INT NOT NULL,
    MaChiNhanh INT NOT NULL,
    TinhTrang ENUM('NEW', 'GOOD', 'DAMAGED', 'LOST') DEFAULT 'NEW',
    ViTriKe VARCHAR(50), -- Vi tri vat ly trong chi nhanh
    TrangThaiHienTai ENUM('AVAILABLE', 'BORROWED', 'MAINTENANCE') DEFAULT 'AVAILABLE',
    FOREIGN KEY (MaDauSach) REFERENCES dau_sach(MaDauSach) ON DELETE CASCADE,
    FOREIGN KEY (MaChiNhanh) REFERENCES chi_nhanh(MaChiNhanh) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ==========================================
-- 4. HE SINH THAI DOC GIA (CRM Layer)
-- ==========================================

CREATE TABLE hang_thanh_vien (
    MaHang INT AUTO_INCREMENT PRIMARY KEY,
    TenHang VARCHAR(50) NOT NULL, -- Basic, Premium, VIP, Enterprise
    SoSachToiDa INT DEFAULT 3,
    SoNgayMuonToiDa INT DEFAULT 7,
    PhiThanhVien DECIMAL(15,2) DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE doc_gia (
    MaDocGia INT AUTO_INCREMENT PRIMARY KEY,
    MaTaiKhoan INT UNIQUE,
    MaHang INT,
    HoTen VARCHAR(100) NOT NULL,
    NgaySinh DATE,
    DienThoai VARCHAR(20),
    Email VARCHAR(100),
    DiaChi VARCHAR(255),
    NgayDangKy DATETIME DEFAULT CURRENT_TIMESTAMP,
    TrangThai ENUM('ACTIVE', 'EXPIRED', 'BANNED') DEFAULT 'ACTIVE',
    FOREIGN KEY (MaTaiKhoan) REFERENCES tai_khoan(MaTaiKhoan) ON DELETE SET NULL,
    FOREIGN KEY (MaHang) REFERENCES hang_thanh_vien(MaHang) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ==========================================
-- 5. GIAO DICH MUON TRA (Transaction Layer)
-- ==========================================

CREATE TABLE phieu_muon (
    MaPhieuMuon INT AUTO_INCREMENT PRIMARY KEY,
    MaDocGia INT NOT NULL,
    MaNhanVien INT NOT NULL, -- TaiKhoan cua Librarian
    MaChiNhanh INT NOT NULL,
    NgayMuon DATETIME DEFAULT CURRENT_TIMESTAMP,
    HanTra DATETIME NOT NULL,
    TrangThai ENUM('BORROWING', 'COMPLETED', 'LATE') DEFAULT 'BORROWING',
    FOREIGN KEY (MaDocGia) REFERENCES doc_gia(MaDocGia) ON DELETE RESTRICT,
    FOREIGN KEY (MaNhanVien) REFERENCES tai_khoan(MaTaiKhoan) ON DELETE RESTRICT,
    FOREIGN KEY (MaChiNhanh) REFERENCES chi_nhanh(MaChiNhanh) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE chi_tiet_muon (
    MaChiTiet INT AUTO_INCREMENT PRIMARY KEY,
    MaPhieuMuon INT NOT NULL,
    MaCuonSach INT NOT NULL,
    NgayTra DATETIME, -- Null neu chua tra
    TinhTrangKhiTra ENUM('GOOD', 'DAMAGED', 'LOST'),
    GhiChu TEXT,
    FOREIGN KEY (MaPhieuMuon) REFERENCES phieu_muon(MaPhieuMuon) ON DELETE CASCADE,
    FOREIGN KEY (MaCuonSach) REFERENCES cuon_sach(MaCuonSach) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ==========================================
-- 6. TAI CHINH & VI PHAM (Finance Layer)
-- ==========================================

CREATE TABLE vi_pham (
    MaViPham INT AUTO_INCREMENT PRIMARY KEY,
    MaPhieuMuon INT,
    MaDocGia INT NOT NULL,
    LoaiViPham ENUM('LATE', 'DAMAGED', 'LOST', 'OTHER'),
    SoTienPhat DECIMAL(15,2) NOT NULL,
    NoiDung TEXT,
    TrangThai ENUM('UNPAID', 'PAID') DEFAULT 'UNPAID',
    NgayGhiNhan DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaPhieuMuon) REFERENCES phieu_muon(MaPhieuMuon) ON DELETE SET NULL,
    FOREIGN KEY (MaDocGia) REFERENCES doc_gia(MaDocGia) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE giao_dich_thanh_toan (
    MaGiaoDich INT AUTO_INCREMENT PRIMARY KEY,
    MaViPham INT NOT NULL,
    SoTien DECIMAL(15,2) NOT NULL,
    PhuongThuc ENUM('CASH', 'VNPAY', 'MOMO', 'BANK_TRANSFER'),
    NgayThanhToan DATETIME DEFAULT CURRENT_TIMESTAMP,
    MaChungTu VARCHAR(100), -- Ma reference tu gateway
    FOREIGN KEY (MaViPham) REFERENCES vi_pham(MaViPham) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ==========================================
-- 7. AUDIT LOGS (Security Layer)
-- ==========================================

CREATE TABLE nhat_ky_he_thong (
    MaLog BIGINT AUTO_INCREMENT PRIMARY KEY,
    MaTaiKhoan INT,
    HanhDong VARCHAR(255) NOT NULL,
    ChiTiet TEXT,
    DiaChiIP VARCHAR(45),
    ThoiGian DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaTaiKhoan) REFERENCES tai_khoan(MaTaiKhoan) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ==============================================================================
-- DỮ LIỆU MẪU (MOCK DATA) - GIẢ LẬP HỆ THỐNG ENTERPRISE TRÊN 3 CHI NHÁNH
-- ==============================================================================

-- 1. Chi Nhanh
INSERT INTO chi_nhanh (TenChiNhanh, DiaChi, DienThoai) VALUES 
('LibSaaS Node 01 - Hanoi Central', '123 Ba Dinh, Hanoi', '024-11112222'),
('LibSaaS Node 02 - HCM Hub', '456 District 1, HCM City', '028-33334444'),
('LibSaaS Node 03 - Da Nang Tech', '789 Hai Chau, Da Nang', '023-55556666');

-- 2. Hang Thanh Vien
INSERT INTO hang_thanh_vien (TenHang, SoSachToiDa, SoNgayMuonToiDa, PhiThanhVien) VALUES 
('Basic (Free)', 2, 7, 0),
('Standard', 5, 14, 50000),
('Premium', 10, 30, 150000),
('Elite (VIP)', 20, 90, 500000),
('Corporate', 100, 180, 2000000);

-- 3. Tai Khoan (Password mat dinh: 123456 - da hash/placeholder)
INSERT INTO tai_khoan (TenDangNhap, MatKhau, HoTen, VaiTro, MaChiNhanh) VALUES 
('admin', '$2y$10$vN9uOaycAgv6fWpAAsByB.r3mY8lYFzVndI5JkC.L0vS9VfBw.m1e', 'Administrator HQ', 'ADMIN', NULL),
('lib1', '$2y$10$vN9uOaycAgv6fWpAAsByB.r3mY8lYFzVndI5JkC.L0vS9VfBw.m1e', 'Le Thu Thu', 'LIBRARIAN', 1),
('lib2', '$2y$10$vN9uOaycAgv6fWpAAsByB.r3mY8lYFzVndI5JkC.L0vS9VfBw.m1e', 'Nguyen Quan Ly', 'LIBRARIAN', 2),
('lib3', '$2y$10$vN9uOaycAgv6fWpAAsByB.r3mY8lYFzVndI5JkC.L0vS9VfBw.m1e', 'Tran Thu Thu', 'LIBRARIAN', 3),
('luan', '$2y$10$vN9uOaycAgv6fWpAAsByB.r3mY8lYFzVndI5JkC.L0vS9VfBw.m1e', 'Nguyễn Thành Luân', 'USER', 1),
('guest', '$2y$10$vN9uOaycAgv6fWpAAsByB.r3mY8lYFzVndI5JkC.L0vS9VfBw.m1e', 'Phan Văn Khách', 'USER', 2);

-- 4. Doc Gia
INSERT INTO doc_gia (MaTaiKhoan, MaHang, HoTen, Email, DienThoai, TrangThai) VALUES 
(5, 4, 'Nguyễn Minh Luân', 'luan@example.com', '0987654321', 'ACTIVE'),
(6, 1, 'Phan Văn Khách', 'khach@example.com', '0123456789', 'ACTIVE');

-- 5. The Loai & Tac Gia
INSERT INTO the_loai (TenTheLoai) VALUES ('Văn Học'), ('Kinh Tế'), ('Khoa Học'), ('Lịch Sử'), ('Công Nghệ');
INSERT INTO tac_gia (TenTacGia) VALUES ('Nguyễn Nhật Ánh'), ('Dale Carnegie'), ('Yuval Noah Harari'), ('Stephen Hawking'), ('Robert Kiyosaki');

-- 6. Dau Sach (15 Titles)
INSERT INTO dau_sach (TenSach, MaTacGia, MaTheLoai, ISBN, NhaXuatBan, NamXuatBan) VALUES 
('Mắt Biếc', 1, 1, '9786041123456', 'NXB Trẻ', 2019),
('Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 1, 1, '9786041123457', 'NXB Trẻ', 2015),
('Đắc Nhân Tâm', 2, 2, '9786041123458', 'NXB Tổng Hợp', 2020),
('Quẳng Gánh Lo Đi Và Vui Sống', 2, 2, '9786041123459', 'NXB Trẻ', 2018),
('Sapiens: Lược Sử Loài Người', 3, 4, '9786041123460', 'NXB Thế Giới', 2017),
('Homo Deus: Lược Sử Tương Lai', 3, 4, '9786041123461', 'NXB Tri Thức', 2019),
('Lược Sử Thời Gian', 4, 3, '9786041123462', 'NXB Trẻ', 2016),
('Cha Giàu Cha Nghèo', 5, 2, '9786041123463', 'NXB Trẻ', 2000),
('Dạy Con Làm Giàu - Tập 2', 5, 2, '9786041123464', 'NXB Trẻ', 2005),
('21 Bài Học Cho Thế Kỷ 21', 3, 4, '9786041123465', 'NXB Thế Giới', 2018),
('Vũ Trụ Trong Lòng Bàn Tay', 4, 3, '9786041123466', 'NXB Trẻ', 2020),
('Cho Tôi Xin Một Vé Đi Tuổi Thơ', 1, 1, '9786041123467', 'NXB Trẻ', 2010),
('Kinh Tế Học Hài Hước', 2, 2, '9786041123468', 'NXB Lao Động', 2014),
('Súng, Vi Trùng Và Thép', 3, 4, '9786041123469', 'NXB Tri Thức', 2015),
('Bản Thiết Kế Vĩ Đại', 4, 3, '9786041123470', 'NXB Trẻ', 2012);

-- 7. Cuon Sach (50 Physical Items across 3 Branches)
-- Mocking multiple copies for "Mat Biec" in Branch 1 & 2
INSERT INTO cuon_sach (MaVach, MaDauSach, MaChiNhanh, TinhTrang, ViTriKe) VALUES 
('BAR-MB-001', 1, 1, 'NEW', 'SHELF-A1'),
('BAR-MB-002', 1, 1, 'GOOD', 'SHELF-A1'),
('BAR-MB-003', 1, 2, 'NEW', 'HUB-B2'),
('BAR-DNT-001', 3, 1, 'NEW', 'FINANCE-01'),
('BAR-SAPI-001', 5, 3, 'NEW', 'TECH-H1'),
('BAR-SAPI-002', 5, 1, 'GOOD', 'SHELF-C3'),
('BAR-TIME-001', 7, 2, 'DAMAGED', 'HUB-C1'),
-- ... Batch Insert placeholder (Tuong tu cho cac dau sach khac de du 50 cuon)
('BAR-AUTO-01', 2, 1, 'NEW', 'A-01'), ('BAR-AUTO-02', 4, 2, 'NEW', 'B-01'),
('BAR-AUTO-03', 6, 3, 'NEW', 'C-01'), ('BAR-AUTO-04', 8, 1, 'NEW', 'D-01'),
('BAR-AUTO-05', 9, 2, 'NEW', 'E-01'), ('BAR-AUTO-06', 10, 3, 'NEW', 'F-01'),
('BAR-AUTO-07', 11, 1, 'NEW', 'G-01'), ('BAR-AUTO-08', 12, 2, 'NEW', 'H-01'),
('BAR-AUTO-09', 13, 3, 'NEW', 'I-01'), ('BAR-AUTO-10', 14, 1, 'NEW', 'J-01');

-- 8. Giao Dich Mau (Complex Scenario)
-- Phieu muon cho Nguyen Thanh Luan tai HN Central
INSERT INTO phieu_muon (MaDocGia, MaNhanVien, MaChiNhanh, NgayMuon, HanTra, TrangThai) VALUES 
(1, 2, 1, '2024-03-20 10:00:00', '2024-04-20 10:00:00', 'BORROWING');

INSERT INTO chi_tiet_muon (MaPhieuMuon, MaCuonSach) VALUES (1, 1), (1, 4), (1, 6);

-- Luan bi phạt do lam hong sach (Mat Biec BAR-MB-001)
INSERT INTO vi_pham (MaPhieuMuon, MaDocGia, LoaiViPham, SoTienPhat, NoiDung, TrangThai) VALUES 
(1, 1, 'DAMAGED', 120000, 'Làm rách trang bìa cuốn Mắt Biếc', 'PAID');

-- Giao dich thanh toan qua Momo cho hinh phat tren
INSERT INTO giao_dich_thanh_toan (MaViPham, SoTien, PhuongThuc, MaChungTu) VALUES 
(1, 120000, 'MOMO', 'MOMO_REF_99887766');

-- Nhật ký hệ thống
INSERT INTO nhat_ky_he_thong (MaTaiKhoan, HanhDong, ChiTiet, DiaChiIP) VALUES 
(1, 'CREATE_DATABASE', 'Initialize LibSaaS Enterprise Schema', '127.0.0.1'),
(2, 'BORROW_ASSET', 'Librarian processed loan for member #1', '192.168.1.10'),
(2, 'FINE_COLLECTION', 'Received 120k for damaged item BAR-MB-001', '192.168.1.10');
