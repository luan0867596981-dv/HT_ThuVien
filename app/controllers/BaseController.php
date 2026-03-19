<?php
class BaseController {
    // Render view with data
    protected function render($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Define paths
        $headerPath = 'app/views/layout/header.php';
        $sidebarPath = 'app/views/layout/sidebar.php';
        $footerPath = 'app/views/layout/footer.php';
        $viewPath = 'app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $headerPath;
            // Only show sidebar if user is logged in
            if (isset($_SESSION['user'])) {
                require_once $sidebarPath;
                echo '<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 pb-5" style="min-height: calc(100vh - 56px); background-color: #f4f6f9;">';
            } else {
                echo '<main class="container pt-4 pb-5" style="min-height: calc(100vh - 56px); background-color: #f4f6f9;">';
            }
            
            require_once $viewPath;

            if (isset($_SESSION['user'])) {
                echo '</main></div></div>'; // Close sidebar divs
            } else {
                echo '</main>';
            }
            require_once $footerPath;
        } else {
            die("View not found: " . $view);
        }
    }

    // Redirect
    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
}
?>
