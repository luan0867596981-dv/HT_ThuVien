<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lịch Sử Trả Sách</h1>
</div>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div class="alert alert-success">Xác nhận trả sách thành công!</div>
<?php endif; ?>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th class="ps-3 border-0 rounded-start">Mã PT</th>
                <th class="border-0">Mã Mượn</th>
                <th class="border-0">Tên Độc Giả</th>
                <th class="border-0">Ngày Trả Thực Tế</th>
                <th class="border-0">Tình Trạng Trả</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($phieuTras as $pt): ?>
            <tr>
                <td class="ps-3 fw-bold text-secondary">#<?= $pt['MaPhieuTra'] ?></td>
                <td><a href="index.php?controller=muonsach&action=index&search=<?= $pt['MaPhieuMuon'] ?>">#<?= $pt['MaPhieuMuon'] ?></a></td>
                <td class="fw-bold text-dark"><?= htmlspecialchars($pt['HoTen']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($pt['NgayTra'])) ?></td>
                <td>
                    <?php if($pt['TrangThai'] == 'HOP_LE'): ?>
                        <span class="badge bg-success">HỢP LỆ</span>
                    <?php elseif($pt['TrangThai'] == 'TRE_HAN'): ?>
                        <span class="badge bg-warning text-dark">TRỄ HẠN</span>
                    <?php elseif($pt['TrangThai'] == 'HU_HONG'): ?>
                        <span class="badge bg-danger">HƯ HỎNG BỒI THƯỜNG</span>
                    <?php else: ?>
                        <span class="badge bg-dark">MẤT SÁCH</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($phieuTras)): ?>
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu trả sách.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
