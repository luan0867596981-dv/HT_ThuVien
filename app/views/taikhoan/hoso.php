<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hồ Sơ Của Tôi</h1>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($profile['HoTen'] ?? $profile['TenDangNhap']) ?>&background=random&size=150" alt="Avatar" class="rounded-circle mb-3 border">
                <h4><?= htmlspecialchars($profile['HoTen'] ?? $profile['TenDangNhap']) ?></h4>
                <p class="text-muted mb-1 badge bg-info"><?= $profile['VaiTro'] ?></p>
                <p class="text-muted">Tham gia: <?= isset($profile['NgayDangKy']) ? date('d/m/Y', strtotime($profile['NgayDangKy'])) : 'N/A' ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Thông tin chi tiết</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th width="30%">Tên đăng nhập:</th>
                            <td><?= htmlspecialchars($profile['TenDangNhap']) ?></td>
                        </tr>
                        <?php if($profile['VaiTro'] == 'USER'): ?>
                        <tr>
                            <th>Họ và tên:</th>
                            <td><?= htmlspecialchars($profile['HoTen']) ?></td>
                        </tr>
                        <tr>
                            <th>Ngày sinh:</th>
                            <td><?= $profile['NgaySinh'] ? date('d/m/Y', strtotime($profile['NgaySinh'])) : 'Chưa cập nhật' ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= htmlspecialchars($profile['Email'] ?? 'Chưa cập nhật') ?></td>
                        </tr>
                        <tr>
                            <th>Điện thoại:</th>
                            <td><?= htmlspecialchars($profile['DienThoai'] ?? 'Chưa cập nhật') ?></td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td><?= htmlspecialchars($profile['DiaChi'] ?? 'Chưa cập nhật') ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-4">
                    <button class="btn btn-primary"><i class="fas fa-edit me-1"></i> Cập nhật thông tin</button>
                    <button class="btn btn-outline-secondary"><i class="fas fa-key me-1"></i> Đổi mật khẩu</button>
                </div>
            </div>
        </div>
    </div>
</div>
