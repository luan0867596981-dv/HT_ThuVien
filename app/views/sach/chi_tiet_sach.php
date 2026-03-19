<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Chi Tiết Sách</h1>
    <div>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['VaiTro'] != 'USER'): ?>
        <a href="index.php?controller=sach&action=sua&id=<?= $sach['MaSach'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Sửa</a>
        <?php endif; ?>
        <a href="index.php?controller=sach&action=index" class="btn btn-secondary">Quay Lại</a>
    </div>
</div>

<div class="card shadow">
    <div class="row g-0">
        <div class="col-md-4 p-4 text-center bg-light border-end">
            <?php if($sach['AnhBia']): ?>
                <img src="<?= $sach['AnhBia'] ?>" class="img-fluid rounded shadow-sm" alt="Bìa sách">
            <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded h-100" style="min-height: 400px;">
                    <i class="fas fa-book fa-5x"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="card-body p-4">
                <h2 class="card-title text-primary mb-3"><?= htmlspecialchars($sach['TenSach']) ?></h2>
                
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>Tác Giả:</strong> <span class="text-muted"><?= htmlspecialchars($sach['TenTacGia'] ?? 'Chưa cập nhật') ?></span></li>
                            <li class="mb-2"><strong>Thể Loại:</strong> <span class="badge bg-info text-dark"><?= htmlspecialchars($sach['TenTheLoai'] ?? 'Chưa phân loại') ?></span></li>
                            <li class="mb-2"><strong>Nhà Xuất Bản:</strong> <?= htmlspecialchars($sach['NhaXuatBan'] ?? 'N/A') ?></li>
                            <li class="mb-2"><strong>Năm Xuất Bản:</strong> <?= htmlspecialchars($sach['NamXuatBan'] ?? 'N/A') ?></li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>ISBN:</strong> <?= htmlspecialchars($sach['ISBN'] ?? 'N/A') ?></li>
                            <li class="mb-2"><strong>Tổng Số Lượng:</strong> <?= htmlspecialchars($sach['SoLuong']) ?> quyển</li>
                            <li class="mb-2"><strong>Vị Trí Kệ:</strong> <span class="badge bg-secondary"><?= htmlspecialchars($sach['ViTriKe'] ?? 'Chưa xác định') ?></span></li>
                            <li class="mb-2">
                                <strong>Trạng Thái:</strong> 
                                <?php if($sach['SoLuong'] > 0): ?>
                                    <span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Có Thể Mượn</span>
                                <?php else: ?>
                                    <span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> Đã Hết</span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr>
                
                <h5 class="mb-3">Mô Tả Lôi Cuốn</h5>
                <p class="card-text text-justify" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars($sach['MoTa'] ?? 'Chưa có thông tin mô tả cho cuốn sách này.')) ?>
                </p>

                <?php if(isset($_SESSION['user']) && $_SESSION['user']['VaiTro'] == 'USER' && $sach['SoLuong'] > 0): ?>
                <div class="mt-4">
                    <!-- Note for USER: they request borrow or something -->
                    <a href="index.php?controller=muonsach&action=dat_truoc&id=<?= $sach['MaSach'] ?>" class="btn btn-lg btn-success">
                        <i class="fas fa-bookmark me-1"></i> Đăng Ký Mượn / Đặt Trước
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
