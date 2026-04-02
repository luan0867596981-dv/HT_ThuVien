<!-- LIBRARIAN REQUEST MANAGER -->
<div class="container-fluid py-4">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm border-start border-4 border-indigo-600">
                <div>
                    <h2 class="mb-1 text-slate-800 fw-bold">Quản Lý Yêu Cầu Mượn</h2>
                    <p class="text-muted mb-0 small">Phê duyệt hoặc từ chối các yêu cầu đặt mượn từ độc giả VIP.</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-circle">
                    <i class="fa-solid fa-list-check text-indigo-600 fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if(empty($pendingRequests)): ?>
    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
        <div class="card-body">
            <div class="p-4 bg-slate-50 rounded-circle d-inline-block mb-3">
                <i class="fa-solid fa-bell-slash text-slate-300 fs-1"></i>
            </div>
            <h4 class="text-slate-700 fw-bold">Hiện không có yêu cầu nào mới</h4>
            <p class="text-muted">Tất cả các bản đăng ký mượn đã được xử lý gọn gàng!</p>
        </div>
    </div>
    <?php else: ?>
    <div class="table-responsive bg-white rounded-4 shadow-sm overflow-hidden border border-slate-100">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-slate-50 text-slate-600 fw-bold small">
                <tr>
                    <th class="px-4 py-3">#ID</th>
                    <th class="py-3">THÔNG TIN SÁCH</th>
                    <th class="py-3">ĐỘC GIẢ</th>
                    <th class="py-3">NGÀY ĐĂNG KÝ</th>
                    <th class="py-3 text-center">HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pendingRequests as $req): ?>
                <tr class="border-bottom border-slate-50">
                    <td class="px-4 py-4 fw-bold text-slate-400">#<?= $req['MaPhieuMuon'] ?></td>
                    <td class="py-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= $req['AnhBia'] ?: 'https://via.placeholder.com/60x90?text=LIB' ?>" 
                                 class="rounded-2 shadow-xs" width="40" height="60" style="object-fit:cover;">
                            <div>
                                <div class="fw-bold text-slate-800"><?= htmlspecialchars($req['TenSach']) ?></div>
                                <div class="text-indigo-600 small"><i class="fa-solid fa-magnifying-glass me-1"></i> ID Sách: <?= $req['MaDauSach'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="fw-bold text-slate-700"><?= htmlspecialchars($req['HoTen'] ?? 'N/A') ?></div>
                        <div class="text-muted small">Mã ĐG: #<?= $req['MaDocGia'] ?></div>
                    </td>
                    <td class="py-4 text-slate-500 small">
                        <i class="fa-solid fa-clock-pulse me-1"></i> <?= date('d/m/Y H:i', strtotime($req['NgayMuon'])) ?>
                    </td>
                    <td class="py-4 text-center px-4">
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-indigo shadow-none btn-sm rounded-3 px-3 py-2 fw-bold" 
                                    data-bs-toggle="modal" data-bs-target="#approveModal<?= $req['MaPhieuMuon'] ?>">
                                <i class="fa-solid fa-check-double me-1"></i> Phê Duyệt
                            </button>
                            <a href="index.php?controller=muonsach&action=tu_choi&id=<?= $req['MaPhieuMuon'] ?>" 
                               class="btn btn-outline-danger shadow-none btn-sm rounded-3 px-3 py-2 fw-bold"
                               onclick="return confirm('Bạn có chắc muốn từ chối yêu cầu này không?')">
                                <i class="fa-solid fa-xmark me-1"></i> Từ Chối
                            </a>
                        </div>

                        <!-- Approval Modal -->
                        <div class="modal fade" id="approveModal<?= $req['MaPhieuMuon'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow-lg">
                                    <form action="index.php?controller=muonsach&action=duyet" method="POST">
                                        <input type="hidden" name="MaPhieuMuon" value="<?= $req['MaPhieuMuon'] ?>">
                                        <div class="modal-header border-bottom border-indigo-50 p-4">
                                            <h5 class="modal-title fw-bold text-slate-800">Cấp Sách Vật Lý</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <div class="alert alert-indigo-subtle border-0 rounded-3 small mb-4">
                                                <i class="fa-solid fa-info-circle me-2"></i> Hãy chọn một mã cuốn sách cụ thể đang có sẵn tại chi nhánh để cấp cho độc giả <b><?= htmlspecialchars($req['HoTen']) ?></b>.
                                            </div>
                                            <label class="form-label fw-bold text-slate-700">Mã Cuốn Sách (Barcode)</label>
                                            <input type="text" name="MaCuonSach" class="form-control rounded-3 border-slate-200" 
                                                   placeholder="Ví dụ: BAR-AUTO-01" required>
                                            <p class="text-muted small mt-2">Mã sách này sẽ được đánh dấu là [BORROWED] sau khi phê duyệt.</p>
                                        </div>
                                        <div class="modal-footer border-0 p-4 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-indigo rounded-pill px-5 fw-bold">Xác Nhận Cấp Sách</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .border-indigo-50 { border-color: #eef2ff; }
    .alert-indigo-subtle { background-color: #eef2ff; color: #4338ca; }
    .text-indigo-600 { color: #4f46e5; }
    .btn-indigo { background-color: #4f46e5; color: white; transition: all 0.3s; border: none; }
    .btn-indigo:hover { background-color: #4338ca; color: white; transform: translateY(-2px); }
    .shadow-xs { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1); }
</style>
