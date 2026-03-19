<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lập Phiếu Mượn Mới</h1>
    <a href="index.php?controller=muonsach&action=index" class="btn btn-secondary">Quay Lại</a>
</div>

<div class="row">
    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?controller=muonsach&action=tao">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Người Mượn <span class="text-danger">*</span></label>
                            <select name="MaDocGia" class="form-select" required>
                                <option value="">-- Chọn Độc Giả --</option>
                                <?php foreach($docgias as $dg): ?>
                                    <option value="<?= $dg['MaDocGia'] ?>"><?= htmlspecialchars($dg['HoTen']) ?> (SĐT: <?= htmlspecialchars($dg['DienThoai'] ?? 'N/A') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Hạn Trả Dự Kiến <span class="text-danger">*</span></label>
                            <input type="date" name="HanTra" class="form-control" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+14 days')) ?>">
                            <div class="form-text">Mặc định là 14 ngày kể từ hôm nay.</div>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 text-primary"><i class="fas fa-book-open me-2"></i>Chọn Sách Mượn</h5>
                    <div class="alert alert-info py-2"><i class="fas fa-info-circle me-1"></i> Có thể chọn nhiều sách. Danh sách bên dưới chỉ hiển thị những sách đang có sẵn trong kho.</div>
                    
                    <div id="book-selection-area">
                        <div class="row book-row mb-3">
                            <div class="col-md-11">
                                <select name="MaSach[]" class="form-select" required>
                                    <option value="">-- Chọn Sách --</option>
                                    <?php foreach($sachList as $s): ?>
                                        <option value="<?= $s['MaSach'] ?>"><?= htmlspecialchars($s['TenSach']) ?> (Còn: <?= $s['SoLuong'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <!-- First row cannot be deleted -->
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btnAddBook" class="btn btn-outline-primary btn-sm mb-4"><i class="fas fa-plus"></i> Thêm sách khác</button>

                    <hr>
                    <button type="submit" class="btn btn-success btn-lg px-5"><i class="fas fa-check me-2"></i> Tạo Phiếu Mượn</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-light shadow-sm">
            <div class="card-header fw-bold">Quy định mượn sách</div>
            <div class="card-body text-muted small">
                <ul class="ps-3 mb-0">
                    <li class="mb-2">Mỗi độc giả được mượn tối đa 5 quyển.</li>
                    <li class="mb-2">Thời gian mượn tối đa là 14 ngày.</li>
                    <li class="mb-2">Nếu quá hạn sẽ phạt tiền 5.000đ/ngày.</li>
                    <li>Sách hư hỏng / mất sẽ phải bồi thường theo giá trị sách.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAddBook = document.getElementById('btnAddBook');
    const container = document.getElementById('book-selection-area');
    
    btnAddBook.addEventListener('click', function() {
        // Clone the first row
        const firstRow = container.querySelector('.book-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset select value
        newRow.querySelector('select').value = '';
        newRow.querySelector('select').removeAttribute('required');
        
        // Add remove button
        const colBtn = newRow.querySelector('.col-md-1');
        colBtn.innerHTML = '<button type="button" class="btn btn-danger btn-sm rounded-circle shadow-sm btn-remove-book"><i class="fas fa-minus"></i></button>';
        
        // Add event listener to new remove button
        colBtn.querySelector('.btn-remove-book').addEventListener('click', function() {
            newRow.remove();
        });
        
        container.appendChild(newRow);
    });
});
</script>
