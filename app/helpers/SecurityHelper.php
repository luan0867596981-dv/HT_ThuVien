<?php
class SecurityHelper {
    /**
     * Tạo CSRF Token ngẫu nhiên và lưu vào session
     */
    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Xác thực CSRF Token từ request
     */
    public static function verifyCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Tạo input hidden chứa CSRF Token cho các form
     */
    public static function csrfInput() {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Làm sạch dữ liệu đầu ra để chống XSS (alias cho htmlspecialchars)
     */
    public static function clean($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
?>
