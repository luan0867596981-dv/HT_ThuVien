<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibSaaS - Quản Lý Thư Viện Chuyên Nghiệp</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-sidebar: #0f172a;
            --text-sidebar: #94a3b8;
            --text-sidebar-hover: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: var(--bg-body) !important;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Top Navbar */
        .saas-topbar {
            background-color: #ffffff;
            /* Bóng đổ mượt mà, không dùng border */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2.5rem;
            z-index: 10;
        }

        /* Responsive Layout Rules */
        @media (min-width: 768px) {
            .saas-layout-active .saas-topbar {
                margin-left: 25%;
                width: 75%;
                position: fixed;
                top: 0;
                right: 0;
            }
            .saas-layout-active main {
                margin-top: 70px !important;
                background-color: transparent !important; /* Override BaseController */
                padding: 1.5rem 2.5rem !important;
            }
            .sidebar-saas {
                position: fixed;
                top: 0;
                left: 0;
                width: 25%;
                height: 100vh;
                z-index: 20;
                overflow-y: auto;
            }
        }
        @media (min-width: 992px) {
            /* Col-lg-2 matching width */
            .saas-layout-active .saas-topbar { margin-left: 16.666667%; width: 83.333333%; }
            .sidebar-saas { width: 16.666667%; }
            .saas-layout-active main { padding: 2rem 3rem !important; }
        }

        .btn-logout {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            color: #ef4444; /* Đỏ mượt */
        }

        /* Sidebar Styles */
        .sidebar-saas {
            background-color: var(--bg-sidebar) !important;
            padding-top: 2rem;
            color: var(--text-sidebar);
            border-right: none !important;
        }
        
        .sidebar-brand {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 2.5rem;
            display: block;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .sidebar-brand:hover { color: #ffffff; }
        .sidebar-brand span { color: #3b82f6; } /* Accent blue */
        
        .sidebar-heading-saas {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            font-weight: 800;
            margin: 1.5rem 1.5rem 0.75rem;
        }

        .sidebar-saas .nav-link {
            color: var(--text-sidebar) !important;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.7rem 1.2rem;
            margin: 0.2rem 1rem;
            border-radius: 0.5rem; /* Bo góc mềm mại */
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        .sidebar-saas .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            margin-right: 0.75rem !important;
        }
        .sidebar-saas .nav-link:hover {
            color: var(--text-sidebar-hover) !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        .sidebar-saas .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            box-shadow: none !important;
        }

        /* Dashboard Cards */
        .saas-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            /* Đổ bóng đa tầng xịn xò */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            cursor: pointer;
        }
        .saas-card:hover {
            transform: translateY(-4px); /* Hover nảy lên nhẹ */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }
        .saas-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .saas-card-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 0;
        }
        .saas-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .saas-card-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 1rem 0 0.5rem;
            line-height: 1;
        }
        .saas-card-trend {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .icon-blue { background-color: #eff6ff; color: #3b82f6; }
        .icon-emerald { background-color: #ecfdf5; color: #10b981; }
        .icon-amber { background-color: #fffbeb; color: #f59e0b; }
        .icon-rose { background-color: #fff1f2; color: #e11d48; }

        .trend-up { color: #10b981; }
        .trend-down { color: #ef4444; }
        .trend-neutral { color: var(--text-muted); }
    </style>
</head>
<body class="<?= isset($_SESSION['user']) ? 'saas-layout-active' : '' ?>">

    <?php if (isset($_SESSION['user'])): ?>
    <!-- Topbar (SaaS mode) -->
    <div class="saas-topbar">
        <div class="d-flex align-items-center">
            <div class="me-4 text-muted d-none d-md-block fw-medium">
                Chào, <strong class="text-dark"><?= htmlspecialchars($_SESSION['user']['HoTen']) ?></strong>
            </div>
            <a class="btn-logout" href="index.php?controller=taikhoan&action=dangxuat">
                <span>Đăng xuất</span>
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </div>
    <?php else: ?>
    <!-- Navbar cho khách -->
    <header class="navbar navbar-light bg-white shadow-sm border-bottom py-3 px-4">
        <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="index.php">
            Lib<span class="text-primary">SaaS</span>
        </a>
        <a class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm" href="index.php?controller=taikhoan&action=dangnhap">
            Đăng nhập
        </a>
    </header>
    <?php endif; ?>

    <div class="container-fluid p-0">
        <div class="row g-0 flex-nowrap">
