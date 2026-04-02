<?php
session_start();

// Cấu hình Database hoặc các Constant cơ bản (nếu có)
// require_once 'config/database.php';

// Route mặc định theo Query String truyền thống
$controller = isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'TrangChu';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerName = $controller . 'Controller';
$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerObject = new $controllerName();
    
    if (method_exists($controllerObject, $action)) {
        $controllerObject->$action();
    } else {
        die("Action not found: " . $action);
    }
} else {
    die("Controller not found: " . $controllerFile);
}
?>
