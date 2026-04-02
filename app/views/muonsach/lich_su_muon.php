<!-- READER LOAN HUB -->
<div class="container-fluid py-4">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm border-start border-4 border-indigo-600">
                <div>
                    <h2 class="mb-1 text-slate-800 fw-bold">Lịch Sử Mượn Sách</h2>
                    <p class="text-muted mb-0 small">Theo dõi chi tiết các cuốn sách bạn đang mượn và đã trả.</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-circle">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-600 fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if(empty($myLoans)): ?>
    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
        <div class="card-body">
            <div class="p-4 bg-slate-50 rounded-circle d-inline-block mb-3">
                <i class="fa-solid fa-book-open text-slate-300 fs-1"></i>
            </div>
            <h4 class="text-slate-700 fw-bold">Bạn chưa mượn cuốn sách nào</h4>
            <p class="text-muted">Hãy khám phá kho sách của thư viện ngay hôm nay nhé!</p>
            <a href="index.php?controller=sach&action=index" class="btn btn-indigo rounded-pill px-4 py-2 mt-3 fw-bold">
                <i class="fa-solid fa-magnifying-glass me-2"></i>Tìm Sách Ngay
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach($myLoans as $loan): ?>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative">
                <div class="card-body p-4 pt-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="flex-shrink-0">
                            <img src="<?= $loan['AnhBia'] ?: 'https://via.placeholder.com/120x180?text=LIB' ?>" 
                                 class="rounded-3 shadow-sm" alt="Book Cover" style="width: 85px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="fw-bold text-slate-800 mb-1"><?= htmlspecialchars($loan['TenSach'] ?: 'Đang chờ duyệt...') ?></h5>
                                <span class="badge rounded-pill px-3 py-2 border 
                                    <?= ($loan['TrangThai'] == 'REQUESTED') ? 'bg-warning text-dark border-warning-subtle' : 
                                        (($loan['TrangThai'] == 'BORROWING') ? 'bg-indigo text-white border-indigo-subtle' : 
                                        (($loan['TrangThai'] == 'COMPLETED') ? 'bg-success text-white border-success-subtle' : 'bg-danger text-white border-danger-subtle')) ?>">
                                    <i class="fa-solid <?= ($loan['TrangThai'] == 'REQUESTED') ? 'fa-hourglass-half' : 'fa-check-circle' ?> me-1"></i>
                                    <?= $loan['TrangThai'] ?>
                                </span>
                            </div>
                            <div class="text-muted small mb-3">Phát hành: <?= date('d/m/Y', strtotime($loan['NgayMuon'])) ?></div>
                            
                            <div class="p-3 bg-slate-50 rounded-3 border border-slate-100 mb-3">
                                <?php if($loan['TrangThai'] == 'REQUESTED'): ?>
                                <div class="text-orange-600 fw-bold small">
                                    <i class="fa-solid fa-bell me-1"></i> Đang chờ Thủ thư kiểm tra kho và duyệt cấp sách cho bạn.
                                </div>
                                <?php else: ?>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-slate-600 small fw-bold">Mã vạch:</span>
                                    <span class="badge bg-white text-slate-700 border border-slate-200"><?= $loan['MaVachList'] ?: 'N/A' ?></span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-slate-200">
                                    <span class="text-danger small fw-bold"><i class="fa-solid fa-calendar-xmark me-1"></i> Hạn trả:</span>
                                    <span class="text-danger fw-bold"><?= date('d/m/Y', strtotime($loan['HanTra'])) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <a href="index.php?controller=sach&action=chitiet&id=<?= $loan['MaDauSach'] ?>" class="text-indigo-600 fw-bold text-decoration-none small hover-link">
                                Xem Thông Tin Sách <i class="fa-solid fa-chevron-right ms-1 mt-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .border-slate-100 { border-color: #f1f5f9; }
    .border-indigo-600 { border-left-color: #4f46e5 !important; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-orange-600 { color: #ea580c; }
    .bg-indigo { background-color: #4f46e5; }
    
    .hover-link:hover { color: #4338ca !important; text-decoration: underline !important; }
    
    .btn-indigo { background-color: #4f46e5; color: white; transition: all 0.3s; border: none; }
    .btn-indigo:hover { background-color: #4338ca; color: white; transform: translateY(-2px); }
</style>
