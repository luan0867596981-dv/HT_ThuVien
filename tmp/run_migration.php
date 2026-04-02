<?php
$configPath = dirname(__DIR__) . '/config/database.php';
if (!file_exists($configPath)) {
    die("Config not found at: " . $configPath);
}
require_once $configPath;

try {
    $db = (new Database())->getConnection();
    // 1. Update phieu_muon STATUS ENUM
    $db->exec("ALTER TABLE phieu_muon MODIFY COLUMN TrangThai ENUM('REQUESTED', 'BORROWING', 'COMPLETED', 'LATE', 'REJECTED') DEFAULT 'REQUESTED'");
    
    // 2. Add MaDauSach to phieu_muon
    $db->exec("ALTER TABLE phieu_muon ADD COLUMN MaDauSach INT NULL AFTER MaDocGia");
    $db->exec("ALTER TABLE phieu_muon MODIFY COLUMN MaNhanVien INT NULL");
    
    echo "Migration Success! [Database Schema v3.1 Applied]";
} catch (Exception $e) {
    echo "Migration Failed: " . $e->getMessage();
}
?>
