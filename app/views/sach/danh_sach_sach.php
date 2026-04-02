<style>
    /* HIGH CONTRAST SAAS DATA GRID SYSTEM */
    .catalog-wrapper {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .catalog-table thead th {
        background-color: #334155 !important; /* bg-slate-700 */
        color: #ffffff !important;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 16px 20px;
        border: none;
    }

    .catalog-table tbody tr {
        background-color: #ffffff;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #e2e8f0;
    }

    .catalog-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .catalog-table tbody tr:hover {
        background-color: #f1f5f9 !important;
    }

    .catalog-table td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
    }

    .asset-title {
        color: #1e293b;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: -0.01em;
    }

    /* BADGE ENHANCEMENTS: VISIBILITY RECOVERY */
    .badge-category-vip {
        background-color: #e0e7ff !important; /* Light Indigo */
        color: #4338ca !important; /* Dark Indigo */
        font-weight: 800;
        font-size: 0.65rem;
        padding: 6px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        border: 1px solid rgba(67, 56, 202, 0.1);
    }

    .badge-inventory-vip {
        background-color: #dcfce7 !important; /* Light Green */
        color: #15803d !important; /* Dark Green */
        font-weight: 800;
        font-size: 0.7rem;
        padding: 6px 12px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-id-vip {
        background-color: #f1f5f9;
        color: #64748b;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Action Buttons Contrast */
    .btn-action-vip {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
        text-decoration: none;
    }
    /* CARD GRID FOR USERS */
    .catalog-grid {
        display: grid;
        grid-template_columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 1.5rem;
    }
    .book-card-user {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px; /* Thêm độ bo góc cho hiện đại */
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .book-card-user:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.08); /* Đổi màu bóng sang indigo nhạt */
        border-color: #818cf8;
    }
</style>

<div class="px-2" style="background: #f8fafc; min-height: 100vh;">

<div class="d-flex justify-content-between align-items-center pt-5 pb-4 px-4">
    <div>
        <h1 class="h3 fw-800 text-slate-800 m-0" style="letter-spacing: -0.04em;">Intelligence Hub</h1>
        <p class="text-muted small fw-600 mb-0">High-fidelity catalog monitoring & asset tracking.</p>
    </div>
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['VaiTro'] != 'USER'): ?>
    <a href="index.php?controller=sach&action=them" class="btn btn-dark d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm border-0" style="background: #334155; font-weight: 700;">
        <i class="fa-solid fa-plus fs-6"></i> <span>Add New Asset</span>
    </a>
    <?php endif; ?>
</div>

<div class="px-4 pb-5">
    <!-- AI SEARCH INTEGRATION FOR ALL ROLES -->
    <div class="mb-4 text-center" style="<?= $_SESSION['user']['VaiTro'] == 'USER' ? 'padding: 2rem 0;' : '' ?>">
        <div class="mx-auto" style="max-width: 700px; position: relative;">
            <i class="fa-solid fa-wand-magic-sparkles text-slate-400" style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); font-size: 1.2rem;"></i>
            <input type="text" id="hybrid-search-input" class="form-control rounded-pill shadow-sm px-5 py-3 fw-700 border-2" 
                   placeholder="<?= $_SESSION['user']['VaiTro'] == 'USER' ? 'Bạn muốn tìm sách gì hôm nay? (Ví dụ: Đắc nhân tâm,...)' : 'Search intelligence parameters...' ?>" 
                   style="font-size: 1.1rem; border-color: #e2e8f0;" autocomplete="off">
            <div id="ai-loading-spinner" class="spinner-border spinner-border-sm text-indigo-600" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); display: none;" role="status"></div>
        </div>
    </div>

    <!-- Data Grid Container -->
    <div class="<?= $_SESSION['user']['VaiTro'] == 'USER' ? '' : 'catalog-wrapper' ?>">
        <?php if($_SESSION['user']['VaiTro'] == 'USER'): ?>
            <!-- CARD VIEW FOR USERS -->
            <div id="catalog-display-area" class="catalog-grid">
                <?php foreach($sachList as $s): ?>
                    <div class="book-card-user">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-indigo-100 rounded-3 p-3 text-indigo-600"><i class="fa-solid fa-book fa-lg"></i></div>
                            <div>
                                <h5 class="mb-0 fw-800 text-slate-800"><?= htmlspecialchars($s['TenSach']) ?></h5>
                                <small class="text-slate-500 fw-bold">Tác giả: <?= htmlspecialchars($s['TenTacGia'] ?? 'N/A') ?></small>
                            </div>
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="badge-category-vip"><?= htmlspecialchars($s['TenTheLoai'] ?? 'GENERAL') ?></span>
                            <span class="text-indigo-600 fw-800 small uppercase"><i class="fa-solid fa-location-dot me-1"></i> <?= $s['ViTriKe'] ?: 'Chưa xếp kệ' ?></span>
                        </div>
                        <a href="index.php?controller=sach&action=chitiet&id=<?= $s['MaDauSach'] ?>" class="btn btn-indigo mt-3 w-100 btn-sm rounded-pill fw-bold">Xem Chi Tiết</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- TABLE VIEW FOR ADMIN/LIBRARIAN -->
            <div class="table-responsive">
                <table class="table catalog-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">UID</th>
                            <th>ẢNH</th>
                            <th>THÔNG TIN SÁCH</th>
                            <th>TÁC GIẢ</th>
                            <th>THỂ LOẠI</th>
                            <th class="text-center">TỒN KHO</th>
                            <th>VỊ TRÍ</th>
                            <th class="pe-4 text-end">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody id="catalog-display-area">
                        <?php if(empty($sachList)): ?>
                            <tr><td colspan="8" class="text-center py-5 text-muted fw-bold fs-5"><i class="fa-solid fa-ghost d-block mb-3 fs-1 opacity-20"></i> No intelligence matching established parameters.</td></tr>
                        <?php endif; ?>

                        <?php foreach($sachList as $s): ?>
                        <tr>
                            <td class="ps-4"><span class="badge-id-vip">#<?= str_pad($s['MaDauSach'] ?? 0, 4, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <?php if($s['AnhBia']): ?>
                                    <img src="<?= $s['AnhBia'] ?>" width="44" height="60" class="rounded-2 shadow-sm border" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-slate-100 rounded-2 text-slate-400 d-flex align-items-center justify-content-center border" style="width: 44px; height: 60px;"><i class="fa-solid fa-box-archive"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="asset-title"><?= htmlspecialchars($s['TenSach']) ?></div>
                                <div class="text-slate-500 small fw-bold">ISBN: <?= htmlspecialchars($s['ISBN'] ?: 'NON-INDEXED') ?></div>
                            </td>
                            <td><div class="fw-700 text-slate-600 small"><?= htmlspecialchars($s['TenTacGia'] ?? 'N/A') ?></div></td>
                            <td><span class="badge-category-vip"><?= htmlspecialchars($s['TenTheLoai'] ?? 'GENERAL') ?></span></td>
                            <td class="text-center">
                                <span class="badge-inventory-vip">
                                    <i class="fa-solid fa-cubes"></i> <?= $s['SoLuong'] ?>
                                </span>
                            </td>
                            <td><span class="text-indigo-600 fw-800 small uppercase"><i class="fa-solid fa-location-dot me-1"></i> <?= !empty($s['ViTriKe']) ? htmlspecialchars($s['ViTriKe']) : '<span class="text-muted">Chưa xếp kệ</span>' ?></span></td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="index.php?controller=sach&action=chitiet&id=<?= $s['MaDauSach'] ?>" class="btn-action-vip" title="Inspect Node"><i class="fa-solid fa-magnifying-glass-plus"></i></a>
                                    <a href="index.php?controller=sach&action=sua&id=<?= $s['MaDauSach'] ?>" class="btn-action-vip text-warning" title="Edit Metadata"><i class="fa-solid fa-pen-nib"></i></a>
                                    <a href="index.php?controller=sach&action=xoa&id=<?= $s['MaDauSach'] ?>" class="btn-action-vip text-danger" onclick="return confirm('Initiate Asset Termination?');" title="Archive Node"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('hybrid-search-input');
    const displayArea = document.getElementById('catalog-display-area');
    const spinner = document.getElementById('ai-loading-spinner');
    let debounceTimer;

    const userRole = '<?= $_SESSION['user']['VaiTro'] ?>';

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const keyword = this.value.trim();
            if(spinner) spinner.style.display = 'block';
            displayArea.style.opacity = '0.4';

            debounceTimer = setTimeout(() => {
                // Hybrid Logic: Table for Admin, AI Cards for User
                const action = (userRole === 'USER') ? 'live_search_ai' : 'live_search';
                
                fetch(`index.php?controller=sach&action=${action}&keyword=${encodeURIComponent(keyword)}`)
                    .then(response => response.text())
                    .then(html => {
                        displayArea.innerHTML = html;
                        displayArea.style.opacity = '1';
                        if(spinner) spinner.style.display = 'none';
                    });
            }, 400);
        });
    }
});
</script>
