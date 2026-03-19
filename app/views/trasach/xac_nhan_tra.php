<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Xác Nhận Trả Sách - Phiếu #<?= $phieuMuon['MaPhieuMuon'] ?></h1>
    <a href="index.php?controller=muonsach&action=index" class="btn btn-secondary">Hủy & Quay Lại</a>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Thông Tin Phiếu Mượn</div>
            <div class="card-body">
                <p><strong>Người Mượn:</strong> <?= htmlspecialchars($phieuMuon['HoTen']) ?></p>
                <p><strong>Ngày Mượn:</strong> <?= date('d/m/Y', strtotime($phieuMuon['NgayMuon'])) ?></p>
                <p><strong>Hạn Trả:</strong> <span class="text-primary fw-bold"><?= date('d/m/Y', strtotime($phieuMuon['HanTra'])) ?></span></p>
                
                <?php if($tienPhatDuKien > 0): ?>
                    <div class="alert alert-danger mt-3">
                        <strong><i class="fas fa-exclamation-triangle"></i> CHÚ Ý QUÁ HẠN:</strong> Đã trễ hạn trả sách. Tiền phạt dự kiến: <strong><?= number_format($tienPhatDuKien) ?> đ</strong>.
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mt-3"><i class="fas fa-check-circle"></i> Trả sách đúng hạn.</div>
                <?php endif; ?>

                <ul class="list-group list-group-flush mt-3 border-top pt-3">
                    <?php foreach($phieuMuon['ChiTiet'] as $ct): ?>
                    <li class="list-group-item d-flex px-0">
                        <img src="<?= $ct['AnhBia'] ?>" width="40" height="50" class="me-3 border">
                        <div>
                            <h6 class="mb-0"><?= htmlspecialchars($ct['TenSach']) ?></h6>
                            <small class="text-muted">Số lượng mượn: <?= $ct['SoLuong'] ?></small>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white fw-bold"><i class="fas fa-check-double me-1"></i> Form Xác Nhận Trả</div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=trasach&action=xac_nhan&id=<?= $phieuMuon['MaPhieuMuon'] ?>">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tình Trạng Sách Nhận Lại</label>
                        <select name="TinhTrangSach" class="form-select form-select-lg mb-3" id="tinhTrangSach">
                            <option value="HOP_LE">Đầy đủ & Nguyên vẹn (Sách dùng tốt)</option>
                            <option value="TRE_HAN" <?= $tienPhatDuKien > 0 ? 'selected' : '' ?>>Trễ hạn (Có phạt phí trễ)</option>
                            <option value="HU_HONG">Hư hỏng (Yêu cầu bồi thường)</option>
                            <option value="MAT_SACH">Mất sách (Yêu cầu bồi thường 100%)</option>
                        </select>
                        <div class="form-text">Mặc định hệ thống tự chọn "Trễ hạn" nếu qua ngày hạn trả.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-danger">Tiền Phạt & Bồi Thường (VND)</label>
                        <input type="number" name="TienPhat" class="form-control form-control-lg text-danger fw-bold" value="<?= $tienPhatDuKien ?>" min="0" step="1000">
                        <div class="form-text">Nhập 0 nếu không có phạt. Nếu hư hỏng/mất sách, vui lòng nhập giá trị bồi thường.</div>
                    </div>

                    <hr>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Bạn chắc chắn muốn hoàn tất giao dịch trả sách này chứ?')">
                            <i class="fas fa-save me-1"></i> LƯU XÁC NHẬN VÀ TRẢ SÁCH VÀO KHO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
