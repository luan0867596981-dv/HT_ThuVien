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
    <?php if($_GET['msg'] == 'paid'): ?><div class="alert alert-success">Xác nhận thanh toán thành công!</div><?php endif; ?>
    <?php if($_GET['msg'] == 'error'): ?><div class="alert alert-danger">Có lỗi xảy ra khi thanh toán!</div><?php endif; ?>
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
                    <?php if($vp['LoaiViPham'] == 'TRE_HAN'): ?>
                        <span class="badge bg-warning text-dark"><i class="far fa-clock"></i> Trễ Hạn</span>
                    <?php elseif($vp['LoaiViPham'] == 'HU_HONG'): ?>
                        <span class="badge bg-danger"><i class="fas fa-heart-broken"></i> Hư Hỏng</span>
                    <?php else: ?>
                        <span class="badge bg-dark"><i class="fas fa-ghost"></i> Mất Sách</span>
                    <?php endif; ?>
                </td>
                <td class="text-end fw-bold text-danger">
                    <?= number_format($vp['TienPhat']) ?> ₫
                </td>
                <td class="text-center">
                    <?php if($vp['TrangThaiThanhToan'] == 'DA_THANH_TOAN'): ?>
                        <span class="badge rounded-pill bg-success"><i class="fas fa-check"></i> ĐÃ THANH TOÁN</span>
                    <?php else: ?>
                        <span class="badge rounded-pill bg-danger"><i class="fas fa-times"></i> CHƯA THANH TOÁN</span>
                    <?php endif; ?>
                </td>
                <td class="pe-3 text-end">
                    <?php if($vp['TrangThaiThanhToan'] == 'CHUA_THANH_TOAN'): ?>
                        <a href="index.php?controller=vipham&action=thanh_toan&id=<?= $vp['MaViPham'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận độc giả này đã đóng đủ tiền phạt?');"><i class="fas fa-money-bill-wave"></i> Thu Tiền</a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-check-double"></i> Đã Thu</button>
                    <?php endif; ?>
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
