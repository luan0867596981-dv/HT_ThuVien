<style>
    /* Modern Minimalist Login UI - LibSaaS Enterprise */
    .login-wrapper {
        min-height: 100vh;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border-radius: 20px; /* rounded-4 */
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
        padding: 40px;
        border: none;
    }

    .login-brand {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }
    .login-brand span { color: #6366f1; }

    .login-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 32px;
        font-weight: 500;
    }

    .form-label-vip {
        font-size: 0.8rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-input-vip {
        width: 100%;
        padding: 12px 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        color: #1e293b;
        transition: all 0.2s ease;
        outline: none;
    }
    .form-input-vip:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .form-input-vip::placeholder { color: #cbd5e1; }

    .btn-login-vip {
        width: 100%;
        padding: 14px;
        background-color: #4f46e5;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        margin-top: 10px;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
    .btn-login-vip:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }
    .btn-login-vip:active { transform: translateY(0); }

    .login-footer {
        margin-top: 32px;
        text-align: center;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }
    .login-footer a {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 700;
    }
    .login-footer a:hover { text-decoration: underline; }

    /* Alert Styling Enrichment */
    .alert-vip {
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        margin-bottom: 24px;
    }
    .alert-danger-vip { background-color: #fef2f2; color: #b91c1c; }
    .alert-success-vip { background-color: #f0fdf4; color: #15803d; }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Brand Header -->
        <a href="index.php" class="login-brand">
            <i class="fa-solid fa-cube text-indigo-500"></i>
            Lib<span>SaaS</span>
        </a>
        <p class="login-subtitle">Chào mừng trở lại. Vui lòng đăng nhập để tiếp tục.</p>

        <!-- Dynamic PHP Feedback -->
        <?php if(isset($error)): ?>
            <div class="alert-vip alert-danger-vip">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
            <div class="alert-vip alert-success-vip">
                <i class="fa-solid fa-circle-check me-2"></i> Đăng ký thành công! Vui lòng đăng nhập.
            </div>
        <?php endif; ?>

        <!-- Internal Post Logic Maintained -->
        <form method="POST" action="index.php?controller=taikhoan&action=dangnhap">
            <div class="mb-4">
                <label class="form-label-vip">Tên đăng nhập</label>
                <input type="text" name="username" class="form-input-vip" required placeholder="admin / luan" autocomplete="off">
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label-vip mb-0">Mật khẩu</label>
                    <a href="#" class="text-indigo-600 fw-bold" style="font-size: 0.75rem; text-decoration: none;">Quên mật khẩu?</a>
                </div>
                <input type="password" name="password" class="form-input-vip" required placeholder="Mặc định: 111111" autocomplete="off">
            </div>

            <div class="mb-4 d-flex align-items-center">
                <input type="checkbox" class="form-check-input me-2 border-slate-300" id="remember" style="width: 18px; height: 18px; cursor: pointer;">
                <label class="form-check-label text-slate-600 small fw-600" for="remember" style="cursor: pointer;">Ghi nhớ phiên đăng nhập</label>
            </div>

            <button type="submit" class="btn-login-vip">ĐĂNG NHẬP HỆ THỐNG</button>
        </form>

        <div class="login-footer">
            Chưa có tài khoản? <a href="index.php?controller=taikhoan&action=dangky">Đăng ký ngay tài khoản mới</a>
        </div>
    </div>
</div>
