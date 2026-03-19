<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản Lý Tài Khoản</h1>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="controller" value="taikhoan">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên đăng nhập, họ tên, email..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'deleted'): ?><div class="alert alert-success mt-3 shadow-sm rounded-3">Xóa tài khoản thành công!</div><?php endif; ?>
<?php endif; ?>

<div class="table-responsive bg-white rounded-4 shadow-sm border border-light">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="ps-4 border-0 rounded-start">ID</th>
                <th class="border-0">Tên Đăng Nhập</th>
                <th class="border-0">Họ & Tên</th>
                <th class="border-0">Liên Hệ</th>
                <th class="border-0 text-center">Vai Trò</th>
                <th class="border-0 text-center">Trạng Thái</th>
                <th class="pe-4 border-0 rounded-end text-end">Hành Động</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($taikhoans as $tk): ?>
            <tr class="align-middle">
                <td class="ps-4 fw-bold text-secondary">#<?= $tk['MaTaiKhoan'] ?></td>
                <td class="fw-bold text-dark"><i class="fa-solid fa-user-circle text-muted me-1"></i> <?= htmlspecialchars($tk['TenDangNhap']) ?></td>
                <td><?= htmlspecialchars($tk['HoTen'] ?? 'N/A') ?></td>
                <td>
                    <div class="small">
                        <i class="fas fa-envelope text-muted me-1"></i> <?= htmlspecialchars($tk['Email'] ?? 'N/A') ?><br>
                        <i class="fas fa-phone text-muted me-1"></i> <?= htmlspecialchars($tk['DienThoai'] ?? 'N/A') ?>
                    </div>
                </td>
                <td class="text-center">
                    <?php if($tk['VaiTro'] == 'ADMIN'): ?>
                        <span class="badge rounded-pill bg-danger shadow-sm"><i class="fa-solid fa-crown me-1"></i> ADMIN</span>
                    <?php elseif($tk['VaiTro'] == 'LIBRARIAN'): ?>
                        <span class="badge rounded-pill bg-info text-dark shadow-sm"><i class="fa-solid fa-book-open-reader me-1"></i> THỦ THƯ</span>
                    <?php else: ?>
                        <span class="badge rounded-pill bg-secondary shadow-sm"><i class="fa-solid fa-user me-1"></i> USER</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if($tk['TrangThai'] == 'ACTIVE'): ?>
                        <span class="badge rounded-pill bg-success shadow-sm">ACTIVE</span>
                    <?php else: ?>
                        <span class="badge rounded-pill bg-dark shadow-sm">LOCKED</span>
                    <?php endif; ?>
                </td>
                <td class="pe-4 text-end">
                    <a href="index.php?controller=taikhoan&action=sua&id=<?= $tk['MaTaiKhoan'] ?>" class="btn btn-sm btn-outline-warning me-1" title="Chỉnh Sửa Tài Khoản">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <?php if($_SESSION['user']['MaTaiKhoan'] != $tk['MaTaiKhoan']): ?>
                        <a href="index.php?controller=taikhoan&action=xoa&id=<?= $tk['MaTaiKhoan'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN tài khoản này? Toàn bộ dữ liệu liên quan sẽ bị mất.');" title="Xóa Tài Khoản">
                            <i class="fas fa-trash"></i>
                        </a>
                    <?php else: ?>
                        <span class="badge bg-light text-muted border">Bạn</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(empty($taikhoans)): ?>
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-users-slash fa-2x mb-2 d-block text-secondary"></i>
                    Không có dữ liệu tài khoản nào!
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
