-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for thu_vien
CREATE DATABASE IF NOT EXISTS `thu_vien` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `thu_vien`;

-- Dumping structure for table thu_vien.chi_tiet_muon
CREATE TABLE IF NOT EXISTS `chi_tiet_muon` (
  `MaPhieuMuon` int NOT NULL,
  `MaSach` int NOT NULL,
  `SoLuong` int DEFAULT '1',
  PRIMARY KEY (`MaPhieuMuon`,`MaSach`),
  KEY `MaSach` (`MaSach`),
  CONSTRAINT `chi_tiet_muon_ibfk_1` FOREIGN KEY (`MaPhieuMuon`) REFERENCES `phieu_muon` (`MaPhieuMuon`) ON DELETE CASCADE,
  CONSTRAINT `chi_tiet_muon_ibfk_2` FOREIGN KEY (`MaSach`) REFERENCES `sach` (`MaSach`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.chi_tiet_muon: ~2 rows (approximately)
INSERT INTO `chi_tiet_muon` (`MaPhieuMuon`, `MaSach`, `SoLuong`) VALUES
	(1, 1, 1),
	(2, 3, 1);

-- Dumping structure for table thu_vien.dat_sach
CREATE TABLE IF NOT EXISTS `dat_sach` (
  `MaDatSach` int NOT NULL AUTO_INCREMENT,
  `MaDocGia` int NOT NULL,
  `MaSach` int NOT NULL,
  `NgayDat` datetime DEFAULT CURRENT_TIMESTAMP,
  `TrangThai` enum('CHO_LAY','DA_HUY','HOAN_THANH') COLLATE utf8mb4_unicode_ci DEFAULT 'CHO_LAY',
  PRIMARY KEY (`MaDatSach`),
  KEY `MaDocGia` (`MaDocGia`),
  KEY `MaSach` (`MaSach`),
  CONSTRAINT `dat_sach_ibfk_1` FOREIGN KEY (`MaDocGia`) REFERENCES `doc_gia` (`MaDocGia`) ON DELETE CASCADE,
  CONSTRAINT `dat_sach_ibfk_2` FOREIGN KEY (`MaSach`) REFERENCES `sach` (`MaSach`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.dat_sach: ~0 rows (approximately)

-- Dumping structure for table thu_vien.doc_gia
CREATE TABLE IF NOT EXISTS `doc_gia` (
  `MaDocGia` int NOT NULL AUTO_INCREMENT,
  `MaTaiKhoan` int DEFAULT NULL,
  `HoTen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgaySinh` date DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DienThoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DiaChi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NgayDangKy` datetime DEFAULT CURRENT_TIMESTAMP,
  `TrangThai` enum('ACTIVE','LOCKED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`MaDocGia`),
  UNIQUE KEY `Email` (`Email`),
  KEY `MaTaiKhoan` (`MaTaiKhoan`),
  CONSTRAINT `doc_gia_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `tai_khoan` (`MaTaiKhoan`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.doc_gia: ~2 rows (approximately)
INSERT INTO `doc_gia` (`MaDocGia`, `MaTaiKhoan`, `HoTen`, `NgaySinh`, `Email`, `DienThoai`, `DiaChi`, `NgayDangKy`, `TrangThai`) VALUES
	(1, 3, 'Nguyễn Văn A', '1990-05-15', 'nguyenvana@gmail.com', '0901234567', 'Hà Nội', '2026-03-15 16:03:08', 'ACTIVE'),
	(2, 4, 'Trần Thị B', '1995-10-20', 'tranthib@gmail.com', '0912345678', 'Hồ Chí Minh', '2026-03-15 16:03:08', 'ACTIVE');

-- Dumping structure for table thu_vien.phieu_muon
CREATE TABLE IF NOT EXISTS `phieu_muon` (
  `MaPhieuMuon` int NOT NULL AUTO_INCREMENT,
  `MaDocGia` int NOT NULL,
  `NgayMuon` datetime DEFAULT CURRENT_TIMESTAMP,
  `HanTra` datetime NOT NULL,
  `TrangThai` enum('DANG_MUON','DA_TRA','QUA_HAN') COLLATE utf8mb4_unicode_ci DEFAULT 'DANG_MUON',
  PRIMARY KEY (`MaPhieuMuon`),
  KEY `MaDocGia` (`MaDocGia`),
  CONSTRAINT `phieu_muon_ibfk_1` FOREIGN KEY (`MaDocGia`) REFERENCES `doc_gia` (`MaDocGia`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.phieu_muon: ~2 rows (approximately)
INSERT INTO `phieu_muon` (`MaPhieuMuon`, `MaDocGia`, `NgayMuon`, `HanTra`, `TrangThai`) VALUES
	(1, 1, '2023-10-01 00:00:00', '2023-10-15 00:00:00', 'DA_TRA'),
	(2, 2, '2023-11-01 00:00:00', '2023-11-15 00:00:00', 'DANG_MUON');

-- Dumping structure for table thu_vien.phieu_tra
CREATE TABLE IF NOT EXISTS `phieu_tra` (
  `MaPhieuTra` int NOT NULL AUTO_INCREMENT,
  `MaPhieuMuon` int NOT NULL,
  `NgayTra` datetime DEFAULT CURRENT_TIMESTAMP,
  `TrangThai` enum('HOP_LE','TRE_HAN','HU_HONG','MAT_SACH') COLLATE utf8mb4_unicode_ci DEFAULT 'HOP_LE',
  PRIMARY KEY (`MaPhieuTra`),
  KEY `MaPhieuMuon` (`MaPhieuMuon`),
  CONSTRAINT `phieu_tra_ibfk_1` FOREIGN KEY (`MaPhieuMuon`) REFERENCES `phieu_muon` (`MaPhieuMuon`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.phieu_tra: ~0 rows (approximately)
INSERT INTO `phieu_tra` (`MaPhieuTra`, `MaPhieuMuon`, `NgayTra`, `TrangThai`) VALUES
	(1, 1, '2023-10-10 00:00:00', 'HOP_LE');

-- Dumping structure for table thu_vien.sach
CREATE TABLE IF NOT EXISTS `sach` (
  `MaSach` int NOT NULL AUTO_INCREMENT,
  `TenSach` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaTacGia` int DEFAULT NULL,
  `MaTheLoai` int DEFAULT NULL,
  `NhaXuatBan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NamXuatBan` int DEFAULT NULL,
  `ISBN` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SoLuong` int DEFAULT '0',
  `ViTriKe` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `AnhBia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`MaSach`),
  UNIQUE KEY `ISBN` (`ISBN`),
  KEY `MaTacGia` (`MaTacGia`),
  KEY `MaTheLoai` (`MaTheLoai`),
  CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`MaTacGia`) REFERENCES `tac_gia` (`MaTacGia`) ON DELETE SET NULL,
  CONSTRAINT `sach_ibfk_2` FOREIGN KEY (`MaTheLoai`) REFERENCES `the_loai` (`MaTheLoai`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.sach: ~4 rows (approximately)
INSERT INTO `sach` (`MaSach`, `TenSach`, `MaTacGia`, `MaTheLoai`, `NhaXuatBan`, `NamXuatBan`, `ISBN`, `SoLuong`, `ViTriKe`, `MoTa`, `AnhBia`) VALUES
	(1, 'Tắt Đèn', 1, 2, 'NXB Văn Học', 1939, 'ISBN-001', 10, 'Kệ A1', 'Tác phẩm văn học hiện thực xuất sắc.', NULL),
	(2, 'Số Đỏ', 2, 2, 'NXB Văn Học', 1936, 'ISBN-002', 8, 'Kệ A2', 'Tiểu thuyết trào phúng.', NULL),
	(3, 'Cha Giàu Cha Nghèo', 3, 3, 'NXB Trẻ', 1997, 'ISBN-003', 15, 'Kệ B1', 'Sách kinh tế tài chính cá nhân.', NULL),
	(4, 'Sapiens - Lược Sử Loài Người', 4, 4, 'NXB Khoa học Xã hội', 2011, 'ISBN-004', 5, 'Kệ C1', 'Cuốn sách lôi cuốn về lịch sử loài người.', NULL);

-- Dumping structure for table thu_vien.tac_gia
CREATE TABLE IF NOT EXISTS `tac_gia` (
  `MaTacGia` int NOT NULL AUTO_INCREMENT,
  `TenTacGia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TieuSu` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`MaTacGia`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.tac_gia: ~4 rows (approximately)
INSERT INTO `tac_gia` (`MaTacGia`, `TenTacGia`, `TieuSu`) VALUES
	(1, 'Ngô Tất Tố', 'Nhà văn hiện thực Việt Nam'),
	(2, 'Vũ Trọng Phụng', 'Nhà văn xuất sắc'),
	(3, 'Robert T. Kiyosaki', 'Tác giả Cha giàu cha nghèo'),
	(4, 'Yuval Noah Harari', 'Tác giả Sapiens');

-- Dumping structure for table thu_vien.tai_khoan
CREATE TABLE IF NOT EXISTS `tai_khoan` (
  `MaTaiKhoan` int NOT NULL AUTO_INCREMENT,
  `TenDangNhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MatKhau` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `VaiTro` enum('ADMIN','LIBRARIAN','USER') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USER',
  `TrangThai` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`MaTaiKhoan`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.tai_khoan: ~4 rows (approximately)
INSERT INTO `tai_khoan` (`MaTaiKhoan`, `TenDangNhap`, `MatKhau`, `VaiTro`, `TrangThai`) VALUES
	(1, 'admin', '$2y$10$YLswQefA6JXTYMM5nH90we9siAtG71I1/LMa5XIkplCF32EMtXmKK', 'ADMIN', 'ACTIVE'),
	(2, 'librarian1', '$2y$10$YLswQefA6JXTYMM5nH90we9siAtG71I1/LMa5XIkplCF32EMtXmKK', 'LIBRARIAN', 'ACTIVE'),
	(3, 'nguyenvana', '$2y$10$YLswQefA6JXTYMM5nH90we9siAtG71I1/LMa5XIkplCF32EMtXmKK', 'USER', 'ACTIVE'),
	(4, 'tranthib', '$2y$10$YLswQefA6JXTYMM5nH90we9siAtG71I1/LMa5XIkplCF32EMtXmKK', 'USER', 'ACTIVE');

-- Dumping structure for table thu_vien.the_loai
CREATE TABLE IF NOT EXISTS `the_loai` (
  `MaTheLoai` int NOT NULL AUTO_INCREMENT,
  `TenTheLoai` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`MaTheLoai`),
  UNIQUE KEY `TenTheLoai` (`TenTheLoai`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.the_loai: ~5 rows (approximately)
INSERT INTO `the_loai` (`MaTheLoai`, `TenTheLoai`) VALUES
	(1, 'Khoa học công nghệ'),
	(3, 'Kinh tế'),
	(4, 'Lịch sử'),
	(5, 'Tâm lý học'),
	(2, 'Văn học');

-- Dumping structure for table thu_vien.thong_bao
CREATE TABLE IF NOT EXISTS `thong_bao` (
  `MaThongBao` int NOT NULL AUTO_INCREMENT,
  `MaTaiKhoan` int NOT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayTao` datetime DEFAULT CURRENT_TIMESTAMP,
  `DaDoc` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`MaThongBao`),
  KEY `MaTaiKhoan` (`MaTaiKhoan`),
  CONSTRAINT `thong_bao_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `tai_khoan` (`MaTaiKhoan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.thong_bao: ~0 rows (approximately)

-- Dumping structure for table thu_vien.vi_pham
CREATE TABLE IF NOT EXISTS `vi_pham` (
  `MaViPham` int NOT NULL AUTO_INCREMENT,
  `MaDocGia` int NOT NULL,
  `LoaiViPham` enum('TRE_HAN','MAT_SACH','HU_HONG') COLLATE utf8mb4_unicode_ci NOT NULL,
  `TienPhat` decimal(10,2) DEFAULT '0.00',
  `TrangThaiThanhToan` enum('CHUA_THANH_TOAN','DA_THANH_TOAN') COLLATE utf8mb4_unicode_ci DEFAULT 'CHUA_THANH_TOAN',
  PRIMARY KEY (`MaViPham`),
  KEY `MaDocGia` (`MaDocGia`),
  CONSTRAINT `vi_pham_ibfk_1` FOREIGN KEY (`MaDocGia`) REFERENCES `doc_gia` (`MaDocGia`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table thu_vien.vi_pham: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
