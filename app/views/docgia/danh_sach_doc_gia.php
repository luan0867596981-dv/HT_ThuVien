<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản Lý Độc Giả</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?controller=docgia&action=them" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Thêm Độc Giả Nội Bộ
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="controller" value="docgia">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên, email, SDT..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'added'): ?><div class="alert alert-success">Thêm độc giả thành công!</div><?php endif; ?>
    <?php if($_GET['msg'] == 'updated'): ?><div class="alert alert-success">Cập nhật độc giả thành công!</div><?php endif; ?>
    <?php if($_GET['msg'] == 'deleted'): ?><div class="alert alert-success">Xóa độc giả thành công!</div><?php endif; ?>
<?php endif; ?>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th class="ps-3 border-0 rounded-start">Mã ĐG</th>
                <th class="border-0">Họ & Tên</th>
                <th class="border-0">Ngày Sinh</th>
                <th class="border-0">Tài Khoản</th>
                <th class="border-0">Liên Hệ</th>
                <th class="border-0">Ngày Lập</th>
                <th class="border-0">Trạng Thái</th>
                <th class="pe-3 border-0 rounded-end text-end">Action</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($docgias as $dg): ?>
            <tr>
                <td class="ps-3 fw-bold text-secondary">#<?= $dg['MaDocGia'] ?></td>
                <td class="fw-bold text-dark"><?= htmlspecialchars($dg['HoTen']) ?></td>
                <td><?= $dg['NgaySinh'] ? date('d/m/Y', strtotime($dg['NgaySinh'])) : 'N/A' ?></td>
                <td>
                    <?php if($dg['TenDangNhap']): ?>
                        <span class="badge bg-info text-dark"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($dg['TenDangNhap']) ?></span>
                    <?php else: ?>
                        <span class="text-muted fst-italic">Khách vãng lai</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="small">
                        <i class="fas fa-envelope text-muted"></i> <?= htmlspecialchars($dg['Email'] ?? 'N/A') ?><br>
                        <i class="fas fa-phone text-muted"></i> <?= htmlspecialchars($dg['DienThoai'] ?? 'N/A') ?>
                    </div>
                </td>
                <td><?= date('d/m/Y', strtotime($dg['NgayDangKy'])) ?></td>
                <td>
                    <?php if($dg['TrangThai'] == 'ACTIVE'): ?>
                        <span class="badge rounded-pill bg-success">HOẠT ĐỘNG</span>
                    <?php else: ?>
                        <span class="badge rounded-pill bg-danger">BỊ KHÓA</span>
                    <?php endif; ?>
                </td>
                <td class="pe-3 text-end">
                    <a href="index.php?controller=docgia&action=sua&id=<?= $dg['MaDocGia'] ?>" class="btn btn-sm btn-outline-warning" title="Chỉnh Sửa"><i class="fas fa-pen"></i></a>
                    <a href="index.php?controller=docgia&action=xoa&id=<?= $dg['MaDocGia'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa thư tịch độc giả này?');" title="Xóa Vĩnh Viễn"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($docgias)): ?>
            <tr>
                <td colspan="8" class="text-center py-4 bg-light text-muted">
                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i> Không có dữ liệu độc giả nào.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const tableBody = document.querySelector('tbody');
    const searchForm = searchInput.closest('form');
    let debounceTimer;

    // Prevent default form submission to avoid page reload
    if(searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const keyword = this.value.trim();
        
        // Show loading state gracefully
        tableBody.style.opacity = '0.5';

        // Debounce 300ms
        debounceTimer = setTimeout(() => {
            fetch(`index.php?controller=docgia&action=live_search&keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    tableBody.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                    tableBody.style.opacity = '1';
                });
        }, 300);
    });
});
</script>
