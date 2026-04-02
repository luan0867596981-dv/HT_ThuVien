<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Dashboard VIP Grid Fix */
    .stat-grid-container {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        padding: 0 1.5rem;
        margin-bottom: 3rem;
    }

    @media (min-width: 640px) { .stat-grid-container { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1200px) { .stat-grid-container { grid-template-columns: repeat(4, 1fr); } }

    .stat-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 1.5rem;
        min-height: 140px;
        height: 100%;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    .stat-label {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .stat-unit { font-size: 0.85rem; color: #94a3b8; font-weight: 600; margin-bottom: 5px; }

    .stat-trend {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        margin-bottom: 8px;
        display: inline-block;
    }
    .trend-up { background-color: #dcfce7; color: #15803d; }
    .trend-down { background-color: #fef2f2; color: #ef4444; }

    .stat-icon-bg {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        font-size: 2rem;
        color: #6366f1;
        opacity: 0.1;
        transition: opacity 0.3s;
    }
    .stat-card:hover .stat-icon-bg { opacity: 0.2; }
</style>

<div class="d-flex justify-content-between align-items-center pt-5 pb-4 px-4">
    <div>
        <h1 class="h3 fw-800 text-slate-800 m-0" style="letter-spacing: -0.04em; font-weight: 800;">Trung Tâm Điều Hành</h1>
        <p class="text-muted small fw-600 mb-0">Tổng quan dữ liệu và hoạt động của thư viện.</p>
    </div>
    <div class="d-none d-md-flex gap-2">
        <a href="index.php?controller=Sach&action=exportToCSV" class="btn btn-outline-secondary btn-sm fw-bold px-3 py-2 rounded-3 border-1 bg-white shadow-sm">
            <i class="fa-solid fa-download me-2"></i> Xuất Báo Cáo CSV
        </a>
    </div>
</div>

<!-- Fixing Broken Cards with CSS Grid -->
<div class="stat-grid-container">
    <!-- Total Books -->
    <a href="index.php?controller=Sach&action=index" class="stat-card shadow-sm border-0 bg-white">
        <div>
            <div class="stat-label">TỔNG SÁCH TRONG KHO</div>
            <div class="stat-value">
                <?= isset($sach) ? number_format($sach) : '0' ?>
                <span class="stat-unit">cuốn</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-slate-50">
            <span class="stat-trend trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i> +2.4%</span>
            <i class="fa-solid fa-book-sparkles stat-icon-bg"></i>
        </div>
    </a>

    <!-- Readers -->
    <a href="index.php?controller=DocGia&action=index" class="stat-card shadow-sm border-0 bg-white">
        <div>
            <div class="stat-label">ĐỘC GIẢ ĐĂNG KÝ</div>
            <div class="stat-value">
                <?= isset($doc_gia) ? number_format($doc_gia) : '0' ?>
                <span class="stat-unit">người</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-slate-50">
            <span class="stat-trend trend-up"><i class="fa-solid fa-users-viewfinder me-1"></i> +12%</span>
            <i class="fa-solid fa-id-badge stat-icon-bg"></i>
        </div>
    </a>

    <!-- Active Loans -->
    <a href="index.php?controller=MuonSach&action=index" class="stat-card shadow-sm border-0 bg-white">
        <div>
            <div class="stat-label">ĐANG CHO MƯỢN</div>
            <div class="stat-value">
                <?= isset($dang_muon) ? number_format($dang_muon) : '0' ?>
                <span class="stat-unit">lượt</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-slate-50">
            <span class="stat-trend trend-down"><i class="fa-solid fa-arrow-trend-down me-1"></i> -0.5%</span>
            <i class="fa-solid fa-truck-ramp-box stat-icon-bg"></i>
        </div>
    </a>

    <!-- Overdue -->
    <a href="index.php?controller=ViPham&action=index" class="stat-card shadow-sm border-0 bg-white">
        <div>
            <div class="stat-label">CẢNH BÁO VI PHẠM</div>
            <div class="stat-value">
                <?= isset($qua_han) ? number_format($qua_han) : '0' ?>
                <span class="stat-unit">trường hợp</span>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-slate-50">
            <span class="stat-trend trend-down"><i class="fa-solid fa-triangle-exclamation me-1"></i> +8%</span>
            <i class="fa-solid fa-shield-virus stat-icon-bg"></i>
        </div>
    </a>
</div>

<!-- Chart System Integration -->
<div class="px-4 mb-5">
    <div class="stat-card border-0 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-800 m-0 text-slate-800">Phân Bổ Kho Sách</h5>
                <p class="text-muted small fw-bold mb-0">Thống kê số lượng sách theo từng thể loại</p>
            </div>
            <div class="badge bg-slate-50 text-slate-500 border px-3 py-2 rounded-2 fw-bold">Chỉ số: Mật độ lưu kho</div>
        </div>
        <div style="height: 300px; position: relative;">
            <canvas id="inventoryChart"></canvas>
        </div>
    </div>
</div>

<div class="row g-4 px-4 pb-5">
    <div class="col-12 col-lg-8">
        <div class="stat-card border-0 shadow-sm" style="min-height: 400px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-800 m-0 text-slate-800">Hoạt Động Gần Đây</h5>
                <span class="badge bg-indigo-50 text-indigo-600 px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">Theo Dõi Trực Tiếp</span>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center flex-grow-1 py-5 opacity-50">
                <i class="fa-solid fa-tower-broadcast fs-1 text-slate-300 mb-3 animate-pulse"></i>
                <p class="text-muted fw-600 small">Đang đồng bộ dữ liệu giao dịch thời gian thực trên toàn hệ thống...</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="stat-card border-0 shadow-sm" style="min-height: 400px;">
            <h5 class="fw-800 mb-4 text-slate-800">Thông Báo Vận Hành</h5>
            <div class="p-3 rounded-3 mb-3 border border-warning border-opacity-25 bg-warning bg-opacity-10">
                <div class="d-flex gap-3">
                    <i class="fa-solid fa-cloud-arrow-up text-warning fs-4 mt-1"></i>
                    <div>
                        <div class="fw-800 text-slate-700 small">Cập Nhật Hạ Tầng DB</div>
                        <p class="text-muted small mb-0 mt-1">Hệ thống cơ sở dữ liệu chính cần được vá lỗi hạ tầng. Thời gian dự kiến: 4 phút.</p>
                    </div>
                </div>
            </div>
            <div class="p-3 rounded-3 mb-3 border border-indigo border-opacity-25 bg-indigo bg-opacity-10">
                <div class="d-flex gap-3">
                    <i class="fa-solid fa-shield-halved text-indigo-600 fs-4 mt-1"></i>
                    <div>
                        <div class="fw-800 text-slate-700 small">Lớp Bảo Vệ Zero-Trust</div>
                        <p class="text-muted small mb-0 mt-1">Mọi yêu cầu truy cập hiện đang được xác thực qua lớp kiểm soát CSRF và Phiên làm việc (Session).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$labels = ['Văn học', 'Kinh tế', 'Lịch sử', 'Công nghệ', 'Nghệ thuật'];
$chartData = [145, 92, 48, 120, 65];
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Phân bổ kho sách',
                data: <?= json_encode($chartData) ?>,
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: '#6366f1',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 45
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { color: '#94a3b8', font: { weight: '600' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { weight: '700' } }
                }
            }
        }
    });
});
</script>
