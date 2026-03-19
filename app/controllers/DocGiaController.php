<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/DocGiaModel.php';

class DocGiaController extends BaseController {
    
    private $docGiaModel;

    public function __construct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }
        $this->docGiaModel = new DocGiaModel();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $docgias = $this->docGiaModel->getAll($search);
        
        $this->render('docgia/danh_sach_doc_gia', [
            'docgias' => $docgias,
            'search' => $search
        ]);
    }

    public function them() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'HoTen' => $_POST['HoTen'],
                'NgaySinh' => $_POST['NgaySinh'],
                'Email' => $_POST['Email'],
                'DienThoai' => $_POST['DienThoai'],
                'DiaChi' => $_POST['DiaChi'],
                'TrangThai' => $_POST['TrangThai']
            ];
            $this->docGiaModel->create($data);
            $this->redirect('index.php?controller=docgia&action=index&msg=added');
        }
        $this->render('docgia/them_doc_gia');
    }

    public function sua() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $docgia = $this->docGiaModel->getById($id);

        if (!$docgia) {
            $this->redirect('index.php?controller=docgia&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'HoTen' => $_POST['HoTen'],
                'NgaySinh' => $_POST['NgaySinh'],
                'Email' => $_POST['Email'],
                'DienThoai' => $_POST['DienThoai'],
                'DiaChi' => $_POST['DiaChi'],
                'TrangThai' => $_POST['TrangThai']
            ];
            $this->docGiaModel->update($id, $data);
            $this->redirect('index.php?controller=docgia&action=index&msg=updated');
        }

        $this->render('docgia/sua_doc_gia', ['docgia' => $docgia]);
    }

    public function xoa() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $this->docGiaModel->delete($id);
        $this->redirect('index.php?controller=docgia&action=index&msg=deleted');
    }

    public function live_search() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $docgias = $this->docGiaModel->getAll($keyword);

        if (empty($docgias)) {
            echo '<tr><td colspan="8" class="text-center py-4 bg-light text-muted"><i class="fas fa-folder-open fa-2x mb-2 d-block"></i> Không tìm thấy dữ liệu độc giả nào phù hợp.</td></tr>';
            exit();
        }

        foreach($docgias as $dg) {
            $ngaySinh = $dg['NgaySinh'] ? date('d/m/Y', strtotime($dg['NgaySinh'])) : 'N/A';
            $ngayLap = date('d/m/Y', strtotime($dg['NgayDangKy']));
            
            $taiKhoanBadge = $dg['TenDangNhap'] ? '<span class="badge bg-info text-dark"><i class="fas fa-user-circle"></i> '.htmlspecialchars($dg['TenDangNhap']).'</span>' : '<span class="text-muted fst-italic">Khách vãng lai</span>';
            $trangThaiBadge = $dg['TrangThai'] == 'ACTIVE' ? '<span class="badge rounded-pill bg-success">HOẠT ĐỘNG</span>' : '<span class="badge rounded-pill bg-danger">BỊ KHÓA</span>';
            $email = htmlspecialchars($dg['Email'] ?? 'N/A');
            $dienThoai = htmlspecialchars($dg['DienThoai'] ?? 'N/A');

            echo '<tr>';
            echo '<td class="ps-3 fw-bold text-secondary">#'.$dg['MaDocGia'].'</td>';
            echo '<td class="fw-bold text-dark">'.htmlspecialchars($dg['HoTen']).'</td>';
            echo '<td>'.$ngaySinh.'</td>';
            echo '<td>'.$taiKhoanBadge.'</td>';
            echo '<td><div class="small"><i class="fas fa-envelope text-muted"></i> '.$email.'<br><i class="fas fa-phone text-muted"></i> '.$dienThoai.'</div></td>';
            echo '<td>'.$ngayLap.'</td>';
            echo '<td>'.$trangThaiBadge.'</td>';
            echo '<td class="pe-3 text-end">';
            echo '<a href="index.php?controller=docgia&action=sua&id='.$dg['MaDocGia'].'" class="btn btn-sm btn-outline-warning me-1" title="Chỉnh Sửa"><i class="fas fa-pen"></i></a>';
            echo '<a href="index.php?controller=docgia&action=xoa&id='.$dg['MaDocGia'].'" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Bạn có chắc chắn muốn xóa thư tịch độc giả này?\');" title="Xóa Vĩnh Viễn"><i class="fas fa-trash"></i></a>';
            echo '</td>';
            echo '</tr>';
        }
        exit();
    }
}
?>
