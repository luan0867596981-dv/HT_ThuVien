<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Đăng Ký Độc Giả Nhanh</h4>
            </div>
            <div class="card-body p-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=taikhoan&action=dangky">
                    <h5 class="mb-3 text-primary border-bottom pb-2">Thông tin tài khoản</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu *</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Xác nhận mật khẩu *</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="6">
                        </div>
                    </div>

                    <h5 class="mb-3 mt-3 text-primary border-bottom pb-2">Thông tin cá nhân</h5>
                    <div class="mb-3">
                        <label class="form-label">Họ và Tên *</label>
                        <input type="text" name="hoten" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="dienthoai" class="form-control">
                        </div>
                    </div>

                    <div class="d-grid mt-4 mb-3">
                        <button type="submit" class="btn btn-success btn-lg">Đăng Ký</button>
                    </div>
                    <div class="text-center">
                        <p>Đã có tài khoản? <a href="index.php?controller=taikhoan&action=dangnhap">Đăng nhập</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
