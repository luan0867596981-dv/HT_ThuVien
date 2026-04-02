<?php
/**
 * SENIOR PHP DEVELOPER: LIBSAAS INTELLIGENCE SYNC TOOL v1.0
 * Tool này giúp đồng bộ danh mục sách từ Database vào file bộ nhớ tĩnh của Chatbot (.txt)
 */

// 1. Cấu hình kết nối Database (Enterprise Sync)
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // 2. Khởi tạo nội dung FAQ nghiệp vụ mặc định (Static Knowledge)
    $txtContent = "FAQ | quy trình, cách mượn | 📖 **Quy trình mượn:** 1. Tra cứu. 2. Lấy sách tại kệ. 3. Quét mã thẻ tại quầy.\n";
    $txtContent .= "FAQ | quy định, mượn sách, tối đa | 📚 **Quy định mượn:** Tối đa 03 cuốn/lần. Thời hạn 14 ngày. Có thể gia hạn 01 lần.\n";
    $txtContent .= "FAQ | trễ hạn, phạt, mất sách, rách | ⚠️ **Xử phạt:** Trễ hạn 5.000đ/ngày. Mất/hỏng đền 100% giá trị sách + 20k phí.\n";

    // 3. Truy vấn lấy toàn bộ sách từ Database (High-fidelity Pull)
    // Synchronized with the Enterprise Schema (dau_sach JOIN tac_gia)
    $sql = "SELECT ds.MaDauSach, ds.TenSach, tg.TenTacGia, 
            (SELECT COUNT(*) FROM cuon_sach cs WHERE cs.MaDauSach = ds.MaDauSach AND cs.TrangThaiHienTai = 'AVAILABLE') as SoLuong,
            (SELECT ViTriKe FROM cuon_sach cs WHERE cs.MaDauSach = ds.MaDauSach LIMIT 1) as ViTriKe
            FROM dau_sach ds 
            LEFT JOIN tac_gia tg ON ds.MaTacGia = tg.MaTacGia";
            
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $sachList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    // 4. Duyệt vòng lặp và nối dữ liệu sách vào file tri thức (.txt)
    foreach ($sachList as $row) {
        // Lọc từ khóa: Chuyển tên sách về chữ thường để Chatbot dễ nhận diện
        $keywords = mb_strtolower($row['TenSach'], 'UTF-8');
        $tenSach = $row['TenSach'];
        $tacGia = $row['TenTacGia'] ?? 'N/A';
        $soLuong = $row['SoLuong'];
        $viTri = $row['ViTriKe'] ?: 'Chưa xếp kệ';
        $maId = $row['MaDauSach'];

        // Nối chuỗi theo định dạng pipe-delimited của Chatbot Engine
        $txtContent .= "SACH | " . $keywords . " | 🏷️ **" . $tenSach . "** - Tác giả: " . $tacGia . " ($soLuong cuốn tại $viTri) - <a href='index.php?controller=sach&action=chitiet&id=" . $maId . "' target='_blank'>Xem chi tiết</a>\n";
        $count++;
    }

    // 5. Ghi dữ liệu vào file bộ nhớ tĩnh (Atomic Write)
    $filePath = 'app/data/chatbot_data.txt';
    
    // Đảm bảo thư mục tồn tại
    if (!is_dir('app/data')) {
        mkdir('app/data', 0777, true);
    }

    $result = file_put_contents($filePath, $txtContent);

    // 6. Thông báo thành công và tự động chuyển hướng
    if ($result !== false) {
        echo "<script>
                alert('Tuyệt vời! Đã cập nhật thành công " . $count . " cuốn sách vào bộ não Chatbot!'); 
                window.location.href='index.php';
              </script>";
    } else {
        echo "<script>
                alert('Lỗi: Không thể ghi dữ liệu vào file chatbot_data.txt. Vui lòng kiểm tra quyền ghi của thư mục app/data!'); 
                window.history.back();
              </script>";
    }

} catch (Exception $e) {
    die("Lỗi đồng bộ dữ liệu: " . $e->getMessage());
}
?>
