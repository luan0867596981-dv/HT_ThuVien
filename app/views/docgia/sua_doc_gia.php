<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cập Nhật Thông Tin Độc Giả</h1>
    <a href="index.php?controller=docgia&action=index" class="btn btn-secondary">Quay Lại</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="index.php?controller=docgia&action=sua&id=<?= $docgia['MaDocGia'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="HoTen" class="form-control" value="<?= htmlspecialchars($docgia['HoTen']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Sinh</label>
                            <input type="date" name="NgaySinh" class="form-control" value="<?= htmlspecialchars($docgia['NgaySinh']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="Email" class="form-control" value="<?= htmlspecialchars($docgia['Email']) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="text" name="DienThoai" class="form-control" value="<?= htmlspecialchars($docgia['DienThoai']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng Thái</label>
                            <select name="TrangThai" class="form-select">
                                <option value="ACTIVE" <?= $docgia['TrangThai'] == 'ACTIVE' ? 'selected' : '' ?>>Hoạt Động (ACTIVE)</option>
                                <option value="LOCKED" <?= $docgia['TrangThai'] == 'LOCKED' ? 'selected' : '' ?>>Khóa (LOCKED)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa Chỉ</label>
                        <textarea name="DiaChi" class="form-control" rows="2"><?= htmlspecialchars($docgia['DiaChi']) ?></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Tài khoản liên kết</h5>
            </div>
            <div class="card-body">
                <?php if($docgia['TenDangNhap']): ?>
                    <p class="text-success"><i class="fas fa-check-circle me-1"></i> Đã liên kết tài khoản: <strong><?= htmlspecialchars($docgia['TenDangNhap']) ?></strong></p>
                    <p>Trạng thái TK: <?= $docgia['TinhTrangTK'] ?></p>
                <?php else: ?>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Độc giả chưa có tài khoản trực tuyến.</p>
                <?php endif; ?>
                <ul class="list-group list-group-flush mt-3 border-top pt-3">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Mã Độc Giả:</span>
                        <strong>#<?= $docgia['MaDocGia'] ?></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Ngày Đăng Ký:</span>
                        <strong><?= date('d/m/Y H:i', strtotime($docgia['NgayDangKy'])) ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
