<?php
$host = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = file_get_contents(__DIR__ . '/database/thu_vien.sql');
    $conn->exec($sql);
    echo "Database initialized successfully!\n";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
