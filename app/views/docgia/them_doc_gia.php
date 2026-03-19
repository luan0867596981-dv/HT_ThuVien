<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Thêm Độc Giả Nội Bộ</h1>
    <a href="index.php?controller=docgia&action=index" class="btn btn-secondary">Quay Lại Danh Sách</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="index.php?controller=docgia&action=them">
                    <div class="mb-3">
                        <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="HoTen" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Sinh</label>
                            <input type="date" name="NgaySinh" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="Email" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input type="text" name="DienThoai" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng Thái</label>
                            <select name="TrangThai" class="form-select">
                                <option value="ACTIVE">Hoạt Động (ACTIVE)</option>
                                <option value="LOCKED">Khóa (LOCKED)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa Chỉ</label>
                        <textarea name="DiaChi" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Lưu Độc Giả</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-2"></i>Lưu ý</h5>
            <p>Việc thêm độc giả ở đây là tạo nhanh một hồ sơ độc giả <strong>KHÔNG</strong> kèm tài khoản đăng nhập hệ thống trực tuyến.</p>
            <p>Độc giả muốn tự mượn online cần phải dùng chức năng <strong>Đăng Ký</strong> ở trang bìa.</p>
        </div>
    </div>
</div>
