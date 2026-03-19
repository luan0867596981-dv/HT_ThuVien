<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4 pb-2 border-bottom">
    <h1 class="h3 fw-bolder text-dark m-0" style="letter-spacing: -0.5px;">Overview</h1>
</div>

<div class="row g-4 mb-4">
    <!-- Card Tổng Sách -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="index.php?controller=sach&action=index" class="saas-card">
            <div class="saas-card-header">
                <h6 class="saas-card-title">Tổng Số Sách</h6>
                <div class="saas-card-icon icon-blue">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
            <div class="saas-card-value"><?= number_format($sach ?? 0) ?></div>
            <div class="saas-card-trend">
                <span class="trend-up"><i class="fa-solid fa-arrow-trend-up me-1"></i> 12%</span>
                <span class="text-muted fw-normal ms-1">so với tháng trước</span>
            </div>
        </a>
    </div>

    <!-- Card Độc Giả -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="index.php?controller=docgia&action=index" class="saas-card">
            <div class="saas-card-header">
                <h6 class="saas-card-title">Độc Giả</h6>
                <div class="saas-card-icon icon-emerald">
                    <i class="fa-solid fa-id-card"></i>
                </div>
            </div>
            <div class="saas-card-value"><?= number_format($doc_gia ?? 0) ?></div>
            <div class="saas-card-trend">
                <span class="trend-up"><i class="fa-solid fa-arrow-up me-1"></i> 5.2%</span>
                <span class="text-muted fw-normal ms-1">đăng ký mới</span>
            </div>
        </a>
    </div>

    <!-- Card Sách Đang Mượn -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="index.php?controller=muonsach&action=index" class="saas-card">
            <div class="saas-card-header">
                <h6 class="saas-card-title">Sách Đang Mượn</h6>
                <div class="saas-card-icon icon-amber">
                    <i class="fa-solid fa-share-square"></i>
                </div>
            </div>
            <div class="saas-card-value"><?= number_format($dang_muon ?? 0) ?></div>
            <div class="saas-card-trend">
                <span class="trend-neutral"><i class="fa-solid fa-minus me-1"></i> 0%</span>
                <span class="text-muted fw-normal ms-1">tuần này</span>
            </div>
        </a>
    </div>

    <!-- Card Quá Hạn -->
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="index.php?controller=vipham&action=index" class="saas-card">
            <div class="saas-card-header">
                <h6 class="saas-card-title">Lượt Quá Hạn</h6>
                <div class="saas-card-icon icon-rose">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>
            <div class="saas-card-value"><?= number_format($qua_han ?? 0) ?></div>
            <div class="saas-card-trend">
                <span class="trend-down"><i class="fa-solid fa-arrow-trend-down me-1"></i> -2.4%</span>
                <span class="text-muted fw-normal ms-1">so với tháng trước</span>
            </div>
        </a>
    </div>
</div>
