<?php
require_once '../config/database.php';
try {
    $db = (new Database())->getConnection();
    // 1. Update phieu_muon STATUS ENUM
    $db->exec("ALTER TABLE phieu_muon MODIFY COLUMN TrangThai ENUM('REQUESTED', 'BORROWING', 'COMPLETED', 'LATE', 'REJECTED') DEFAULT 'REQUESTED'");
    
    // 2. Add MaDauSach to phieu_muon for Booking requests (since physical copy is not assigned yet)
    // Actually better to have MaDauSach in phieu_muon if it's a request, 
    // or just leave chi_tiet_muon empty until approval?
    // Let's add MaDauSach to phieu_muon as nullable for REQUESTED status.
    $db->exec("ALTER TABLE phieu_muon ADD COLUMN MaDauSach INT NULL AFTER MaDocGia");
    $db->exec("ALTER TABLE phieu_muon MODIFY COLUMN MaNhanVien INT NULL"); // Make staff nullable for requests
    
    echo "Migration Success!";
} catch (Exception $e) {
    echo "Migration Failed: " . $e->getMessage();
}
?>
