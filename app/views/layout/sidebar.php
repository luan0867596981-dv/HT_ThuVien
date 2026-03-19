<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar-saas collapse">
    <a href="index.php" class="sidebar-brand">
        Lib<span>SaaS</span>
    </a>
    
    <ul class="nav flex-column mb-auto mt-2">
        <li class="nav-item">
            <a class="nav-link <?= (isset($_GET['controller']) && $_GET['controller'] == 'trangchu') || !isset($_GET['controller']) ? 'active' : '' ?>" href="index.php?controller=trangchu&action=index">
                <i class="fa-solid fa-layer-group"></i> Bảng Điều Khiển
            </a>
        </li>
        
        <?php if(isset($_SESSION['user']['VaiTro']) && in_array($_SESSION['user']['VaiTro'], ['ADMIN', 'LIBRARIAN'])): ?>
        <h6 class="sidebar-heading-saas">Core Data</h6>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'sach' ? 'active' : '' ?>" href="index.php?controller=sach&action=index">
                <i class="fa-solid fa-book"></i> Quản Lý Sách
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'docgia' ? 'active' : '' ?>" href="index.php?controller=docgia&action=index">
                <i class="fa-solid fa-id-card"></i> Quản Lý Độc Giả
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'taikhoan' && (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : '' ?>" href="index.php?controller=taikhoan&action=index">
                <i class="fa-solid fa-user-shield"></i> Quản Lý Tài Khoản
            </a>
        </li>
        
        <h6 class="sidebar-heading-saas">Nghiệp Vụ</h6>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'muonsach' ? 'active' : '' ?>" href="index.php?controller=muonsach&action=index">
                <i class="fa-solid fa-share-square"></i> Mượn Sách
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'trasach' ? 'active' : '' ?>" href="index.php?controller=trasach&action=index">
                <i class="fa-solid fa-undo-alt"></i> Trả Sách
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'vipham' ? 'active' : '' ?>" href="index.php?controller=vipham&action=index">
                <i class="fa-solid fa-file-invoice-dollar"></i> Vi Phạm
            </a>
        </li>
        <?php endif; ?>

        <?php if(isset($_SESSION['user']['VaiTro']) && $_SESSION['user']['VaiTro'] == 'USER'): ?>
        <h6 class="sidebar-heading-saas">Dành Cho Độc Giả</h6>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'sach' && isset($_GET['action']) && $_GET['action'] == 'timkiem' ? 'active' : '' ?>" href="index.php?controller=sach&action=timkiem">
                <i class="fa-solid fa-search"></i> Tra Cứu Sách
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'muonsach' && isset($_GET['action']) && $_GET['action'] == 'lichsu' ? 'active' : '' ?>" href="index.php?controller=muonsach&action=lichsu">
                <i class="fa-solid fa-history"></i> Lịch Sử Mượn
            </a>
        </li>
        <?php endif; ?>

        <div class="mt-4 px-4"><hr style="border-color: #334155; opacity: 1;"></div>
        
        <li class="nav-item mt-2">
            <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'taikhoan' && isset($_GET['action']) && $_GET['action'] == 'hoso' ? 'active' : '' ?>" href="index.php?controller=taikhoan&action=hoso">
                <i class="fa-solid fa-user-cog"></i> Hồ Sơ Của Tôi
            </a>
        </li>
    </ul>
</nav>
