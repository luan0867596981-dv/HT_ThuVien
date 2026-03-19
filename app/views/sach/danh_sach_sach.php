<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản Lý Sách</h1>
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['VaiTro'] != 'USER'): ?>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="index.php?controller=sach&action=them" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Thêm Sách Mới
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="controller" value="sach">
            <input type="hidden" name="action" value="<?= isset($_GET['action']) ? $_GET['action'] : 'index' ?>">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm tên sách, tác giả, thể loại..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'added'): ?><div class="alert alert-success">Thêm sách thành công!</div><?php endif; ?>
    <?php if($_GET['msg'] == 'updated'): ?><div class="alert alert-success">Cập nhật sách thành công!</div><?php endif; ?>
    <?php if($_GET['msg'] == 'deleted'): ?><div class="alert alert-success">Xóa sách thành công!</div><?php endif; ?>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên Sách</th>
                <th>Tác Giả</th>
                <th>Thể Loại</th>
                <th>Tồn Kho</th>
                <th>Vị Trí</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sachList as $s): ?>
            <tr>
                <td><?= $s['MaSach'] ?></td>
                <td>
                    <?php if($s['AnhBia']): ?>
                        <img src="<?= $s['AnhBia'] ?>" width="50" height="70" style="object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white text-center d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;"><i class="fas fa-image"></i></div>
                    <?php endif; ?>
                </td>
                <td class="fw-bold"><?= htmlspecialchars($s['TenSach']) ?></td>
                <td><?= htmlspecialchars($s['TenTacGia'] ?? 'N/A') ?></td>
                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($s['TenTheLoai'] ?? 'N/A') ?></span></td>
                <td>
                    <span class="badge <?= $s['SoLuong'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                        <?= $s['SoLuong'] ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($s['ViTriKe']) ?></td>
                <td>
                    <a href="index.php?controller=sach&action=chitiet&id=<?= $s['MaSach'] ?>" class="btn btn-sm btn-info text-white" title="Chi Tiết"><i class="fas fa-eye"></i></a>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['VaiTro'] != 'USER'): ?>
                        <a href="index.php?controller=sach&action=sua&id=<?= $s['MaSach'] ?>" class="btn btn-sm btn-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                        <a href="index.php?controller=sach&action=xoa&id=<?= $s['MaSach'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa cuốn sách này?');" title="Xóa"><i class="fas fa-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($sachList)): ?>
            <tr>
                <td colspan="8" class="text-center">Không tìm thấy sách nào.</td>
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
        
        // Show loading state gracefully (optional)
        tableBody.style.opacity = '0.5';

        // Debounce 300ms
        debounceTimer = setTimeout(() => {
            fetch(`index.php?controller=sach&action=live_search&keyword=${encodeURIComponent(keyword)}`)
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
