<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản Lý Phiếu Mượn Sách</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?controller=muonsach&action=tao" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Lập Phiếu Mượn Mới
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="controller" value="muonsach">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo mã PM, tên người mượn..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'created'): ?>
    <div class="alert alert-success">Lập phiếu mượn sách thành công!</div>
<?php endif; ?>

<div class="row">
    <?php foreach($phieuMuons as $pm): ?>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 <?= $pm['TrangThai'] == 'LATE' ? 'border-danger' : ($pm['TrangThai'] == 'COMPLETED' ? 'border-success' : 'border-primary') ?>">
                <div class="card-header d-flex justify-content-between align-items-center <?= $pm['TrangThai'] == 'LATE' ? 'bg-danger text-white' : ($pm['TrangThai'] == 'COMPLETED' ? 'bg-success text-white' : 'bg-primary text-white') ?>">
                    <h5 class="mb-0">Phiếu: #<?= $pm['MaPhieuMuon'] ?></h5>
                    <span class="badge bg-light text-dark fw-bold"><?= $pm['TrangThai'] ?></span>
                </div>
                <div class="card-body">
                    <p class="mb-1"><i class="fas fa-user text-muted border border-secondary rounded p-1 me-2 pt-0 pb-0 shadow-sm bg-light"></i> <strong>Người Mượn:</strong> <?= htmlspecialchars($pm['HoTen']) ?></p>
                    <p class="mb-1"><i class="fas fa-phone text-muted border border-secondary rounded p-1 me-2 pt-0 pb-0 shadow-sm bg-light"></i> <strong>SĐT:</strong> <?= htmlspecialchars($pm['DienThoai'] ?? 'N/A') ?></p>
                    <p class="mb-1 text-secondary"><i class="far fa-calendar-alt text-muted border border-secondary rounded p-1 me-2 pt-0 pb-0 shadow-sm bg-light"></i> <strong>Ngày Mượn:</strong> <?= date('d/m/Y H:i', strtotime($pm['NgayMuon'])) ?></p>
                    <p class="mb-3 <?= strtotime($pm['HanTra']) < time() && $pm['TrangThai'] != 'COMPLETED' ? 'text-danger fw-bold' : 'text-primary' ?>"><i class="fas fa-calendar-check border border-secondary rounded p-1 me-2 pt-0 pb-0 shadow-sm bg-light"></i> <strong>Hạn Trả:</strong> <?= date('d/m/Y', strtotime($pm['HanTra'])) ?></p>
                    
                    <h6 class="border-bottom pb-2">Danh Sách Mượn:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach($pm['ChiTiet'] as $ct): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 border-0">
                            <div><i class="fas fa-book-open text-muted me-2 small"></i> <?= htmlspecialchars($ct['TenSach']) ?></div>
                            <span class="badge bg-secondary rounded-pill"><?= $ct['MaVach'] ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <?php if($pm['TrangThai'] != 'COMPLETED'): ?>
                <div class="card-footer bg-white text-end">
                    <a href="index.php?controller=trasach&action=xac_nhan&id=<?= $pm['MaPhieuMuon'] ?>" class="btn btn-success btn-sm"><i class="fas fa-check-circle me-1"></i> Xác nhận trả sách</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if(empty($phieuMuons)): ?>
        <div class="col-12"><div class="alert alert-info border">Không có phiếu mượn nào.</div></div>
    <?php endif; ?>
</div>
