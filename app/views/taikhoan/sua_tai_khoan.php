<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2">Chỉnh Sửa Tài Khoản</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?controller=taikhoan&action=index" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="index.php?controller=taikhoan&action=sua&id=<?= $taikhoan['MaTaiKhoan'] ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Tên Đăng Nhập</label>
                        <input type="text" class="form-control form-control-lg bg-light" value="<?= htmlspecialchars($taikhoan['TenDangNhap']) ?>" disabled readonly>
                        <div class="form-text">Tên đăng nhập không thể thay đổi.</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted small text-uppercase">Vai Trò</label>
                            <select name="VaiTro" class="form-select form-select-lg" <?= ($taikhoan['MaTaiKhoan'] == $_SESSION['user']['MaTaiKhoan']) ? 'disabled' : '' ?>>
                                <option value="USER" <?= $taikhoan['VaiTro'] == 'USER' ? 'selected' : '' ?>>Độc giả (USER)</option>
                                <option value="LIBRARIAN" <?= $taikhoan['VaiTro'] == 'LIBRARIAN' ? 'selected' : '' ?>>Thủ thư (LIBRARIAN)</option>
                                <option value="ADMIN" <?= $taikhoan['VaiTro'] == 'ADMIN' ? 'selected' : '' ?>>Quản trị (ADMIN)</option>
                            </select>
                            <?php if($taikhoan['MaTaiKhoan'] == $_SESSION['user']['MaTaiKhoan']): ?>
                                <input type="hidden" name="VaiTro" value="ADMIN">
                                <div class="form-text text-warning">Bạn không thể tự đổi vai trò của mình.</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Trạng Thái</label>
                            <select name="TrangThai" class="form-select form-select-lg" <?= ($taikhoan['MaTaiKhoan'] == $_SESSION['user']['MaTaiKhoan']) ? 'disabled' : '' ?>>
                                <option value="ACTIVE" <?= $taikhoan['TrangThai'] == 'ACTIVE' ? 'selected' : '' ?>>Hoạt động (ACTIVE)</option>
                                <option value="INACTIVE" <?= $taikhoan['TrangThai'] == 'INACTIVE' ? 'selected' : '' ?>>Tạm khóa (INACTIVE)</option>
                            </select>
                            <?php if($taikhoan['MaTaiKhoan'] == $_SESSION['user']['MaTaiKhoan']): ?>
                                <input type="hidden" name="TrangThai" value="ACTIVE">
                                <div class="form-text text-warning">Bạn không thể tự khóa tài khoản của mình.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr class="text-black-50 my-4">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary small text-uppercase">
                            <i class="fa-solid fa-key me-1"></i> Mật khẩu mới
                        </label>
                        <input type="password" name="mat_khau_moi" class="form-control form-control-lg" placeholder="Nhập để đổi mật khẩu...">
                        <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu của tài khoản này!</div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                            <i class="fa-solid fa-save me-2"></i> Lưu Thay Đổi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
