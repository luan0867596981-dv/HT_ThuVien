<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibSaaS VIP - Hệ Thống Thư Viện Thông Minh</title>
    <!-- FAILSAFE CDN RECOVERY -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #f8fafc;
            --midnight-slate: #0f172a;
            --accent-indigo: #6366f1;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-hairline: #e2e8f0;
            --sidebar-text: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: var(--bg-body) !important;
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* 1. SaaS TOPBAR (bg-white, 70px) */
        .saas-header {
            background-color: #ffffff;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-hairline);
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
        }

        /* 2. SIDEBAR (#0f172a Deep Slate) */
        .sidebar-saas {
            background-color: var(--midnight-slate) !important;
            min-height: 100vh;
            color: var(--sidebar-text);
            padding-top: 1.5rem;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-brand-saas {
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            text-decoration: none;
        }
        .sidebar-brand-saas span { color: var(--accent-indigo); }

        .sidebar-saas .nav-link {
            color: var(--sidebar-text) !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .sidebar-saas .nav-link:hover, .sidebar-saas .nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: var(--accent-indigo);
        }
        .sidebar-saas .nav-link i { width: 22px; text-align: center; }

        /* 3. CARDS & TABLES */
        .card-saas, .vip-card {
            background: #ffffff;
            border: 1px solid var(--border-hairline);
            border-radius: 12px; /* rounded-3 */
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            padding: 1.5rem;
            transition: transform 0.3s;
            text-decoration: none;
            color: inherit;
        }
        .vip-card:hover { transform: translateY(-4px); }

        .vip-card-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); font-weight: 800; margin-bottom: 0.5rem; }
        .vip-card-value { font-size: 2rem; font-weight: 800; color: var(--text-main); display: flex; align-items: flex-end; gap: 8px; }
        .vip-badge-mini { font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; background: #ecfdf5; color: #10b981; }
        .vip-badge-neg { background: #fef2f2; color: #ef4444; }

        .table thead th {
            background-color: #1e293b !important;
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 12px 15px;
            border: none;
        }
        .table tbody td { padding: 12px 15px; vertical-align: middle; }

        /* Helpers */
        .hover-lift:hover { transform: translateY(-2px); }
        .text-indigo-600 { color: var(--accent-indigo) !important; }
        .fw-800 { font-weight: 800; }
    </style>
</head>
<body>

<header class="saas-header">
    <div class="d-flex align-items-center">
        <!-- Sidebar Toggle (Mobile) -->
        <button class="navbar-toggler d-md-none border-0 me-3 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <i class="fa-solid fa-bars-staggered fs-4"></i>
        </button>
        <div class="d-none d-md-block text-muted small fw-bold uppercase tracking-widest" style="letter-spacing: 0.1em; font-size: 0.7rem;">
            LIB<span class="text-indigo-600">SAAS</span> TRÌNH ĐIỀU KHIỂN v2.1
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <?php if(isset($_SESSION['user'])): ?>
            <div class="d-flex align-items-center gap-3 me-2">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold small" style="line-height: 1;"><?= htmlspecialchars($_SESSION['user']['HoTen']) ?></div>
                    <span class="text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
                        <?php 
                            $role = $_SESSION['user']['VaiTro']; 
                            echo ($role == 'ADMIN' ? 'Quản trị viên' : ($role == 'LIBRARIAN' ? 'Thủ thư' : 'Độc giả'));
                        ?>
                    </span>
                </div>
                <div class="avatar-sm" style="width: 38px; height: 38px; background: var(--accent-indigo); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                    <?= strtoupper(substr($_SESSION['user']['HoTen'] ?? 'U', 0, 1)) ?>
                </div>
            </div>
            <a href="index.php?controller=TaiKhoan&action=dangxuat" class="btn btn-outline-secondary btn-sm rounded-3 px-3 border-0 py-2" title="Đăng xuất">
                <i class="fa-solid fa-power-off"></i>
            </a>
        <?php else: ?>
            <?php 
                $isLoginPage = (isset($_GET['controller']) && strtolower($_GET['controller']) == 'taikhoan' && (isset($_GET['action']) && in_array($_GET['action'], ['dangnhap', 'dangky'])));
            ?>
            <?php if(!$isLoginPage): ?>
            <a href="index.php?controller=TaiKhoan&action=dangnhap" class="btn text-white px-4 fw-bold rounded-3 shadow-sm border-0" style="background: var(--accent-indigo);">
                Bắt Đầu Ngay
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
