<?php
// We don't want the default layout here, so we close the container and row opened by BaseController
// Normally, a real application would have a separate 'auth_layout' for these pages.
// We'll reset the layout styling specifically for this page using inline CSS to override.
?>
<style>
    /* Reset layout for full page split-screen */
    body { padding: 0 !important; margin: 0 !important; overflow: hidden; background-color: #fff !important; }
    .saas-topbar, .sidebar-saas { display: none !important; }
    .saas-layout-active main { padding: 0 !important; margin-top: 0 !important; max-width: 100% !important; }
    
    .split-container {
        display: flex;
        min-height: calc(100vh - 80px); /* Trừ đi chiều cao của thanh header */
        width: 100vw;
    }

    .left-panel {
        flex: 1;
        background: linear-gradient(135deg, #0f172a 0%, #312e81 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }

    /* Abstract background pattern */
    .left-panel::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 60%);
        transform: rotate(30deg);
        pointer-events: none;
    }

    .right-panel {
        flex: 1;
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem 2rem;
    }

    .login-box {
        width: 100%;
        max-width: 420px;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    /* Floating Labels customizations */
    .form-floating > .form-control {
        border-radius: 0.75rem;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .form-floating > .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .form-floating > label {
        color: #64748b;
        font-weight: 500;
    }
    
    .btn-saas {
        background-color: #0f172a;
        color: white;
        border-radius: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        padding: 0.8rem;
    }
    .btn-saas:hover {
        background-color: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        color: white;
    }

    .social-btn {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        font-weight: 600;
        color: #475569;
        transition: all 0.2s ease;
    }
    .social-btn:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Hide the parent layout's container-fluid classes that break full width */
    .container-fluid, .row { margin: 0; padding: 0; }
</style>

<div class="split-container">
    <!-- Nửa trái: Visual / Branding -->
    <div class="left-panel d-none d-lg-flex">
        <div class="text-center z-1">
            <div class="bg-primary text-white rounded-3 p-3 mb-4 d-inline-block shadow-lg" style="width: 72px; height: 72px; display: flex !important; align-items: center; justify-content: center;">
                <i class="fas fa-book-reader fa-2x"></i>
            </div>
            <h1 class="display-4 fw-bolder mb-3" style="letter-spacing: -1px;">Lib<span class="text-primary">SaaS</span></h1>
            <p class="lead text-white-50 fw-medium" style="max-width: 400px;">Nền tảng quản lý thư viện thông minh, tự động hóa nghiệp vụ và báo cáo trực quan.</p>
        </div>
    </div>

    <!-- Nửa phải: Form Đăng Nhập -->
    <div class="right-panel">
        <div class="login-box">
            <div class="text-center mb-5 d-lg-none">
                <h2 class="fw-bolder text-dark">Lib<span class="text-primary">SaaS</span></h2>
            </div>

            <div class="mb-5">
                <h3 class="fw-bolder text-dark mb-2">Chào mừng trở lại! 👋</h3>
                <p class="text-muted">Vui lòng đăng nhập vào tài khoản của bạn.</p>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                <div class="alert alert-success rounded-3 border-0 bg-success-subtle text-success p-3 mb-4 d-flex align-items-center shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> Đăng ký thành công! Vui lòng đăng nhập.
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger rounded-3 border-0 bg-danger-subtle text-danger p-3 mb-4 d-flex align-items-center shadow-sm">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=taikhoan&action=dangnhap">
                <div class="form-floating mb-4">
                    <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Tên đăng nhập" required>
                    <label for="floatingInput">Tên đăng nhập</label>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Mật khẩu" required>
                    <label for="floatingPassword">Mật khẩu</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="rememberMe" checked>
                        <label class="form-check-label text-muted fw-medium small" for="rememberMe" style="cursor: pointer;">Nhớ tài khoản</label>
                    </div>
                    <a href="#" class="text-primary text-decoration-none fw-medium small">Quên mật khẩu?</a>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-saas btn-lg">Đăng Nhập</button>
                </div>

                <div class="position-relative mb-4">
                    <hr class="text-black-50">
                    <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small fw-medium">
                        HOẶC TIẾP TỤC VỚI
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <button type="button" class="btn social-btn w-100 py-2 d-flex justify-content-center align-items-center gap-2">
                            <i class="fab fa-google text-danger"></i> Google
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn social-btn w-100 py-2 d-flex justify-content-center align-items-center gap-2">
                            <i class="fab fa-github text-dark"></i> Github
                        </button>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <span class="text-muted fw-medium">Chưa có tài khoản? </span>
                    <a href="index.php?controller=taikhoan&action=dangky" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
     Note: Since base controller wraps this in existing main/div containers, we used negative 
     margins/CSS resets at the top of this file to break out of them for the login screen.
-->
