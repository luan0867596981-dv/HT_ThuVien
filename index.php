<?php
session_start();

// Simple MVC Router
$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'TrangChuController';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        die("<h1>Lỗi 404</h1><p>Không tìm thấy hành động (Action not found).</p>");
    }
} else {
    die("<h1>Lỗi 404</h1><p>Không tìm thấy trang (Controller not found).</p>");
}
?>
