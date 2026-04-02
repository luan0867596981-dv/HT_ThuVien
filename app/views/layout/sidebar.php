<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar-saas collapse border-end-0">
    <div class="sidebar-sticky">
        <a href="index.php?controller=TrangChu&action=index" class="sidebar-brand-saas mb-4">
            <i class="fa-solid fa-cube me-2 text-indigo-500"></i>
            Lib<span>SaaS</span>
        </a>
        
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['controller']) && strtolower($_GET['controller']) == 'trangchu') || !isset($_GET['controller']) ? 'active' : '' ?>" href="index.php?controller=TrangChu&action=index">
                    <i class="fa-solid fa-house"></i> 
                    <span>Bảng Điều Khiển</span>
                </a>
            </li>

            <?php if(isset($_SESSION['user']['VaiTro']) && in_array($_SESSION['user']['VaiTro'], ['ADMIN', 'LIBRARIAN'])): ?>
                <div class="px-4 mt-4 mb-2 text-uppercase fw-800 small text-slate-500" style="letter-spacing: 0.1em; font-size: 0.6rem; opacity: 0.5;">QUẢN LÝ KHO SÁCH</div>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'sach' && (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : '' ?>" href="index.php?controller=Sach&action=index">
                        <i class="fa-solid fa-book-bookmark"></i> 
                        <span>Quản Lý Sách</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'docgia' ? 'active' : '' ?>" href="index.php?controller=DocGia&action=index">
                        <i class="fa-solid fa-user-group"></i> 
                        <span>Quản Lý Độc Giả</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'trasach' ? 'active' : '' ?>" href="index.php?controller=TraSach&action=index">
                        <i class="fa-solid fa-right-left"></i> 
                        <span>Mượn Trả Sách</span>
                    </a>
                </li>

                <div class="px-4 mt-4 mb-2 text-uppercase fw-800 small text-slate-500" style="letter-spacing: 0.1em; font-size: 0.6rem; opacity: 0.5;">VẬN HÀNH</div>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'muonsach' && (isset($_GET['action']) && $_GET['action'] == 'yeu_cau') ? 'active' : '' ?>" href="index.php?controller=MuonSach&action=yeu_cau">
                        <i class="fa-solid fa-list-check"></i> 
                        <span>Quản Lý Yêu Cầu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'muonsach' && (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : '' ?>" href="index.php?controller=MuonSach&action=index">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> 
                        <span>Lưu Thông Sách</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'vipham' ? 'active' : '' ?>" href="index.php?controller=ViPham&action=index">
                        <i class="fa-solid fa-triangle-exclamation"></i> 
                        <span>Quản Lý Vi Phạm</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(isset($_SESSION['user']['VaiTro']) && $_SESSION['user']['VaiTro'] == 'USER'): ?>
                <div class="px-4 mt-4 mb-2 text-uppercase fw-800 small text-slate-500" style="letter-spacing: 0.1em; font-size: 0.6rem; opacity: 0.5;">DÀNH CHO ĐỘC GIẢ</div>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'sach' && (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : '' ?>" href="index.php?controller=Sach&action=index">
                        <i class="fa-solid fa-book-open"></i> 
                        <span>Danh Mục Sách</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'sach' && isset($_GET['action']) && $_GET['action'] == 'tracuu_ai' ? 'active' : '' ?>" href="index.php?controller=Sach&action=tracuu_ai">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> 
                        <span>Tra Cứu AI</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'muonsach' && isset($_GET['action']) && $_GET['action'] == 'lich_su' ? 'active' : '' ?>" href="index.php?controller=MuonSach&action=lich_su">
                        <i class="fa-solid fa-clock-rotate-left"></i> 
                        <span>Lịch Sử Mượn</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="mt-5 pt-4 px-3 border-top border-secondary opacity-5"></div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'taikhoan' && isset($_GET['action']) && $_GET['action'] == 'hoso' ? 'active' : '' ?>" href="index.php?controller=TaiKhoan&action=hoso">
                    <i class="fa-solid fa-circle-user"></i> 
                    <span>Hồ Sơ Cá Nhân</span>
                </a>
            </li>
            <?php if(isset($_SESSION['user']['VaiTro']) && $_SESSION['user']['VaiTro'] == 'ADMIN'): ?>
            <li class="nav-item">
                <a class="nav-link <?= isset($_GET['controller']) && strtolower($_GET['controller']) == 'taikhoan' && isset($_GET['action']) && $_GET['action'] == 'index' ? 'active' : '' ?>" href="index.php?controller=TaiKhoan&action=index">
                    <i class="fa-solid fa-shield-halved"></i> 
                    <span>Cài Đặt Hệ Thống</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
