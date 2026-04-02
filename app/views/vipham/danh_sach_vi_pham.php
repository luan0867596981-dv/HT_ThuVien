<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản Lý Vi Phạm & Tiền Phạt</h1>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="controller" value="vipham">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm tên, SĐT, mã vi phạm..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'paid'): ?><div class="alert alert-success shadow-sm"><i class="fas fa-check-circle"></i> Xác nhận thanh toán thành công!</div><?php endif; ?>
    <?php if($_GET['msg'] == 'emailed'): ?><div class="alert alert-warning shadow-sm"><i class="fas fa-paper-plane"></i> Đã gửi email nhắc nhở cho độc giả này.</div><?php endif; ?>
    <?php if($_GET['msg'] == 'blocked'): ?><div class="alert alert-danger shadow-sm"><i class="fas fa-user-lock"></i> Đã khóa quyền mượn sách của độc giả này.</div><?php endif; ?>
    <?php if($_GET['msg'] == 'error'): ?><div class="alert alert-danger">Có lỗi xảy ra khi thực hiện thao tác!</div><?php endif; ?>
<?php endif; ?>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th class="ps-3 border-0 rounded-start">Mã VP</th>
                <th class="border-0">Tên Độc Giả</th>
                <th class="border-0">Liên Hệ</th>
                <th class="border-0">Loại Vi Phạm</th>
                <th class="border-0 text-end">Tiền Phạt</th>
                <th class="border-0 text-center">Trạng Thái</th>
                <th class="pe-3 border-0 rounded-end text-end">Hành Động</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($viPhams as $vp): ?>
            <tr>
                <td class="ps-3 fw-bold text-secondary">#<?= $vp['MaViPham'] ?></td>
                <td class="fw-bold text-dark"><a href="index.php?controller=docgia&action=sua&id=<?= $vp['MaDocGia'] ?>"><?= htmlspecialchars($vp['HoTen']) ?></a></td>
                <td>
                    <div class="small">
                        <i class="fas fa-phone text-muted"></i> <?= htmlspecialchars($vp['DienThoai'] ?? 'N/A') ?>
                    </div>
                </td>
                <td>
                    <?php if($vp['LoaiViPham'] == 'LATE'): ?>
                        <span class="badge bg-warning text-dark"><i class="far fa-clock"></i> Trễ Hạn</span>
                    <?php elseif($vp['LoaiViPham'] == 'DAMAGED'): ?>
                        <span class="badge bg-danger"><i class="fas fa-heart-broken"></i> Hư Hỏng</span>
                    <?php elseif($vp['LoaiViPham'] == 'LOST'): ?>
                        <span class="badge bg-dark"><i class="fas fa-ghost"></i> Mất Sách</span>
                    <?php else: ?>
                        <span class="badge bg-secondary"><i class="fas fa-question-circle"></i> Khác</span>
                    <?php endif; ?>
                </td>
                <td class="text-end fw-bold text-danger">
                    <?= number_format($vp['SoTienPhat'] ?? 0) ?> ₫
                </td>
                <td class="text-center">
                    <?php if(($vp['TrangThai'] ?? '') == 'PAID'): ?>
                        <span class="badge rounded-pill bg-success"><i class="fas fa-check"></i> ĐÃ THANH TOÁN</span>
                    <?php else: ?>
                        <span class="badge rounded-pill bg-danger"><i class="fas fa-times"></i> CHƯA THANH TOÁN</span>
                    <?php endif; ?>
                </td>
                <td class="pe-3 text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <!-- TASK 3: LIVE ACTION HUB -->
                        <a href="index.php?controller=vipham&action=gui_email&id=<?= $vp['MaViPham'] ?>" class="btn btn-sm btn-warning text-dark" title="Gửi Email nhắc nhở" onclick="return confirm('Bạn muốn gửi mail nhắc nhở độc giả này?');">
                            <i class="fas fa-envelope"></i>
                        </a>
                        
                        <a href="index.php?controller=vipham&action=khoa_quyen&id=<?= $vp['MaViPham'] ?>" class="btn btn-sm btn-danger" title="Khóa quyền mượn" onclick="return confirm('XÁC NHẬN: Khóa quyền mượn sách của sinh viên này?');">
                            <i class="fas fa-user-lock"></i>
                        </a>
                        
                        <?php if(($vp['TrangThai'] ?? '') == 'UNPAID'): ?>
                            <a href="index.php?controller=vipham&action=thanh_toan&id=<?= $vp['MaViPham'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận thu phí phạt?');" title="Thu phí phạt">
                                <i class="fas fa-money-bill-wave"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary" disabled title="Đã thu phí"><i class="fas fa-check-double"></i></button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($viPhams)): ?>
            <tr>
                <td colspan="7" class="text-center py-4 bg-light text-muted">
                    <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i> Không có dữ liệu vi phạm. Thật tuyệt vời!
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- SWEETALERT 2 INTEGRATION FOR PREMIUM TOASTS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'paid'): ?>
            Toast.fire({ icon: 'success', title: 'Xác nhận thanh toán thành công!' });
        <?php elseif($_GET['msg'] == 'emailed'): ?>
            Toast.fire({ icon: 'info', title: 'Đã gửi email nhắc nhở thành công!' });
        <?php elseif($_GET['msg'] == 'blocked'): ?>
            Toast.fire({ icon: 'warning', title: 'Đã khóa quyền mượn sách thành công!' });
        <?php elseif($_GET['msg'] == 'error'): ?>
            Toast.fire({ icon: 'error', title: 'Đã có lỗi xảy ra!' });
        <?php endif; ?>
    <?php endif; ?>
});
</script>
