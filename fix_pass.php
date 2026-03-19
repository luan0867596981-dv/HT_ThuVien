<?php
// Thông tin kết nối CSDL (Mặc định của Laragon thường là root và pass rỗng)
$host = 'localhost';
$dbname = 'thu_vien';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Tạo mã hash CHUẨN cho mật khẩu "123456"
    $matKhauGoc = '123456';
    $matKhauHash = password_hash($matKhauGoc, PASSWORD_DEFAULT);

    // 2. Cập nhật mã hash mới này cho TOÀN BỘ tài khoản
    $sql = "UPDATE tai_khoan SET MatKhau = :hash";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['hash' => $matKhauHash]);

    echo "<h3>✅ Thành công!</h3>";
    echo "<p>Đã cập nhật toàn bộ mật khẩu trong database thành: <b>$matKhauGoc</b></p>";
    echo "<p>Mã Hash mới của bạn là: <br> <code>$matKhauHash</code></p>";
    echo "<p style='color:red;'>⚠️ <b>Lưu ý:</b> Hãy copy đoạn mã Hash ở trên để làm Bước 2, sau đó <b>XÓA file fix_pass.php này đi</b> để bảo mật nhé!</p>";

} catch (PDOException $e) {
    echo "Lỗi kết nối CSDL: " . $e->getMessage();
}
?>
