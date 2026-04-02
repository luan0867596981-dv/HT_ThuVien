<!-- LIBSAAS VIP PROFILING SYSTEM -->
<div class="container-fluid py-4">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between p-4 bg-white rounded-4 shadow-sm border-start border-4 border-indigo-600">
                <div>
                    <h2 class="mb-1 text-slate-800 fw-bold">Hồ Sơ Cá Nhân</h2>
                    <p class="text-muted mb-0 small">Quản lý thông tin tài khoản và bảo mật của bạn tại LibSaaS.</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-circle">
                    <i class="fa-solid fa-user-gear text-indigo-600 fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-<?= ($_GET['msg'] == 'profile_updated') ? 'success' : 'danger' ?> alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fa-solid <?= ($_GET['msg'] == 'profile_updated') ? 'fa-circle-check' : 'fa-circle-exclamation' ?> me-2 fs-5"></i>
            <div>
                <?php 
                    if($_GET['msg'] == 'profile_updated') echo "Chúc mừng! Hồ sơ của bạn đã được cập nhật thành công.";
                    elseif($_GET['msg'] == 'password_mismatch') echo "Lỗi: Mật khẩu xác nhận không khớp.";
                    else echo "Có lỗi xảy ra trong quá trình cập nhật. Vui lòng thử lại.";
                ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="bg-indigo-600 py-5 text-center position-relative">
                    <div class="position-absolute top-100 start-50 translate-middle">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($profile['HoTen'] ?? $profile['TenDangNhap']) ?>&background=4f46e5&color=fff&size=120" 
                             class="rounded-circle border border-4 border-white shadow-lg" alt="Avatar">
                    </div>
                </div>
                <div class="card-body pt-5 mt-4 text-center">
                    <h4 class="fw-bold text-slate-800 mb-1"><?= htmlspecialchars($profile['HoTen'] ?? $profile['TenDangNhap']) ?></h4>
                    <span class="badge bg-indigo-100 text-indigo-700 rounded-pill px-3 py-2 mb-3 border border-indigo-200">
                        <i class="fa-solid fa-shield-halved me-1"></i> <?= $profile['VaiTro'] ?>
                    </span>
                    <hr class="my-4 opacity-10">
                    <div class="row text-start px-3">
                        <div class="col-12 mb-3">
                            <label class="text-muted small fw-bold text-uppercase ls-1">Tên Đăng Nhập</label>
                            <div class="text-slate-700 fw-semibold">@<?= htmlspecialchars($profile['TenDangNhap']) ?></div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small fw-bold text-uppercase ls-1">Trạng Thái Thẻ</label>
                            <div class="text-success fw-bold">
                                <i class="fa-solid fa-circle-check me-1"></i> ACTIVE
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small fw-bold text-uppercase ls-1">Ngày Tham Gia</label>
                            <div class="text-slate-600 small"><?= isset($profile['NgayDangKy']) ? date('d/m/Y', strtotime($profile['NgayDangKy'])) : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-0">
                    <ul class="nav nav-pills" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button" role="tab">
                                <i class="fa-solid fa-id-card me-2"></i>Thông Tin Khoản
                            </button>
                        </li>
                        <li class="nav-item ms-2" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                                <i class="fa-solid fa-lock me-2"></i>Bảo Mật
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 pt-2">
                    <form action="index.php?controller=taikhoan&action=cap_nhat_ho_so" method="POST">
                        <div class="tab-content" id="profileTabsContent">
                            <!-- Basic Info Tab -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold text-slate-700">Họ và Tên</label>
                                        <input type="text" name="hoten" class="form-control rounded-3 border-slate-200" value="<?= htmlspecialchars($profile['HoTen'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-slate-700">Email</label>
                                        <input type="email" name="email" class="form-control rounded-3 border-slate-200" value="<?= htmlspecialchars($profile['Email'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-slate-700">Số Điện Thoại</label>
                                        <input type="text" name="dienthoai" class="form-control rounded-3 border-slate-200" value="<?= htmlspecialchars($profile['DienThoai'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold text-slate-700">Địa Chỉ Thường Trú</label>
                                        <textarea name="diachi" class="form-control rounded-3 border-slate-200" rows="3"><?= htmlspecialchars($profile['DiaChi'] ?? '') ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-slate-700">Ngày Sinh</label>
                                        <input type="date" name="ngaysinh" class="form-control rounded-3 border-slate-200" value="<?= $profile['NgaySinh'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="security" role="tabpanel">
                                <div class="p-4 bg-slate-50 rounded-4 mb-4 border border-slate-100">
                                    <div class="d-flex align-items-center text-slate-700 fw-bold mb-3">
                                        <i class="fa-solid fa-key me-2 text-indigo-600"></i>Thay Đổi Mật Khẩu
                                    </div>
                                    <p class="text-muted small">Để trống nếu bạn không muốn thay đổi mật khẩu hiện tại.</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-slate-600">Mật Khẩu Mới</label>
                                            <input type="password" name="mat_khau_moi" class="form-control rounded-3 border-slate-200 shadow-none" placeholder="********">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-slate-600">Xác Nhận Mật Khẩu</label>
                                            <input type="password" name="xac_nhan_mat_khau" class="form-control rounded-3 border-slate-200 shadow-none" placeholder="********">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 text-end">
                            <button type="submit" class="btn btn-indigo px-5 py-2 fw-bold rounded-3 shadow-indigo">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu Thay Đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .border-slate-200 { border-color: #e2e8f0; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .ls-1 { letter-spacing: 0.05em; }
    
    .btn-indigo { background-color: #4f46e5; color: white; transition: all 0.3s; border: none; }
    .btn-indigo:hover { background-color: #4338ca; color: white; transform: translateY(-2px); }
    .shadow-indigo { box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4); }
    
    .nav-pills .nav-link.active { background-color: #4f46e5 !important; }
    .nav-pills .nav-link { color: #64748b; }
    .nav-pills .nav-link:hover { color: #4f46e5; }
</style>
