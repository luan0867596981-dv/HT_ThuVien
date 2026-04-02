<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cập Nhật Thông Tin Sách</h1>
    <a href="index.php?controller=sach&action=index" class="btn btn-secondary">Quay Lại</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="index.php?controller=sach&action=sua&id=<?= $sach['MaSach'] ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tên Sách <span class="text-danger">*</span></label>
                    <input type="text" name="TenSach" class="form-control" value="<?= htmlspecialchars($sach['TenSach']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tác Giả</label>
                    <select name="MaTacGia" class="form-select">
                        <option value="">-- Chọn Tác Giả --</option>
                        <?php foreach($tacgias as $tg): ?>
                            <option value="<?= $tg['MaTacGia'] ?>" <?= $sach['MaTacGia'] == $tg['MaTacGia'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tg['TenTacGia']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Thể Loại</label>
                    <select name="MaTheLoai" class="form-select">
                        <option value="">-- Chọn Thể Loại --</option>
                        <?php foreach($theloais as $tl): ?>
                            <option value="<?= $tl['MaTheLoai'] ?>" <?= $sach['MaTheLoai'] == $tl['MaTheLoai'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tl['TenTheLoai']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nhà Xuất Bản</label>
                    <input type="text" name="NhaXuatBan" class="form-control" value="<?= htmlspecialchars($sach['NhaXuatBan']) ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Năm XB</label>
                    <input type="number" name="NamXuatBan" class="form-control" value="<?= $sach['NamXuatBan'] ?>" min="1000" max="<?= date('Y') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="ISBN" class="form-control" value="<?= htmlspecialchars($sach['ISBN']) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Số Lượng</label>
                    <input type="number" name="SoLuong" class="form-control" value="<?= $sach['SoLuong'] ?>" min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Vị Trí Kệ</label>
                    <input type="text" name="ViTriKe" class="form-control" value="<?= htmlspecialchars($sach['ViTriKe']) ?>">
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Mô Tả</label>
                    <textarea name="MoTa" class="form-control" rows="3"><?= htmlspecialchars($sach['MoTa']) ?></textarea>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh Bìa Hiện Tại</label>
                <?php if($sach['AnhBia']): ?>
                    <div><img src="<?= $sach['AnhBia'] ?>" width="100"></div>
                <?php endif; ?>
                <label class="form-label mt-2">Thay Đổi Ảnh Bìa</label>
                <input type="file" name="AnhBia" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Cập Nhật Sách</button>
        </form>
    </div>
</div>
