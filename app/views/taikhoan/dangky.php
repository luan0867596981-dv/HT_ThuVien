<style>
    /* Modern Minimalist Register UI - LibSaaS Enterprise (Synced with Login) */
    .register-wrapper {
        min-height: 100vh;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .register-card {
        width: 100%;
        max-width: 680px; /* Slightly wider for registration data grid */
        background: #ffffff;
        border-radius: 24px; /* rounded-4 */
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
        padding: 40px;
        border: none;
    }

    .register-brand {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }
    .register-brand span { color: #6366f1; }

    .register-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 32px;
        font-weight: 500;
    }

    .form-group-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #6366f1;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-top: 2rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.5rem;
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

    .btn-register-vip {
        width: 100%;
        padding: 16px;
        background-color: #6366f1; /* Synced primary indigo */
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        margin-top: 20px;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
    }
    .btn-register-vip:hover {
        background-color: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 12px 20px -3px rgba(99, 102, 241, 0.3);
    }

    .register-footer {
        margin-top: 32px;
        text-align: center;
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }
    .register-footer a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 700;
    }

    /* Alert Sync */
    .alert-vip {
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-danger-vip { background-color: #fef2f2; color: #b91c1c; }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <!-- Brand Header Spacing Fix -->
        <div class="text-center text-md-start mb-4">
            <a href="index.php" class="register-brand justify-content-center justify-content-md-start">
                <i class="fa-solid fa-cube text-indigo-500"></i>
                Lib<span>SaaS</span>
            </a>
            <p class="register-subtitle">Đăng ký tham gia cộng đồng tri thức số LibSaaS.</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert-vip alert-danger-vip">
                <i class="fa-solid fa-circle-exclamation fs-5"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=taikhoan&action=dangky">
            
            <div class="form-group-title mt-0">Dữ liệu định danh tài khoản</div>
            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label-vip">Họ và Tên chủ sở hữu *</label>
                    <input type="text" name="hoten" class="form-input-vip" required placeholder="Nhập tên đầy đủ của bạn">
                </div>
                <div class="col-md-12">
                    <label class="form-label-vip">Tên đăng nhập (ID tài khoản) *</label>
                    <input type="text" name="username" class="form-input-vip" required placeholder="Ví dụ: luanvn2024">
                </div>
                <div class="col-md-6">
                    <label class="form-label-vip">Mật khẩu bảo mật *</label>
                    <input type="password" name="password" class="form-input-vip" required minlength="6" placeholder="••••••••">
                </div>
                <div class="col-md-6">
                    <label class="form-label-vip">Xác nhận mật khẩu *</label>
                    <input type="password" name="confirm_password" class="form-input-vip" required minlength="6" placeholder="••••••••">
                </div>
            </div>

            <div class="form-group-title">Thông tin liên lạc & Liên kết</div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label-vip">Địa chỉ Email</label>
                    <input type="email" name="email" class="form-input-vip" placeholder="name@example.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label-vip">Số điện thoại</label>
                    <input type="text" name="dienthoai" class="form-input-vip" placeholder="+84 000 000 000">
                </div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn-register-vip">XÁC NHẬN ĐĂNG KÝ THÀNH VIÊN</button>
            </div>

            <div class="register-footer pe-md-4">
                Bạn đã sở hữu tài khoản? <a href="index.php?controller=taikhoan&action=dangnhap">Đăng nhập ngay hệ thống</a>
            </div>
        </form>
    </div>
</div>
