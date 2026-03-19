<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/SachModel.php';
require_once 'app/models/TheLoaiModel.php';
require_once 'app/models/TacGiaModel.php';

class SachController extends BaseController {
    
    private $sachModel;
    private $theLoaiModel;
    private $tacGiaModel;

    public function __construct() {
        $this->sachModel = new SachModel();
        $this->theLoaiModel = new TheLoaiModel();
        $this->tacGiaModel = new TacGiaModel();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sachList = $this->sachModel->getAll($search);
        
        $this->render('sach/danh_sach_sach', [
            'sachList' => $sachList,
            'search' => $search
        ]);
    }

    public function timkiem() {
        // Alias for index but maybe different view for USER
        $this->index();
    }

    public function them() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Upload image handling (simplified)
            $anhBia = '';
            if (isset($_FILES['AnhBia']) && $_FILES['AnhBia']['error'] === 0) {
                // In a real app we'd validate and move to public/images/
                $anhBia = 'public/images/' . time() . '_' . $_FILES['AnhBia']['name'];
                move_uploaded_file($_FILES['AnhBia']['tmp_name'], $anhBia);
            }

            $data = [
                'TenSach' => $_POST['TenSach'],
                'MaTacGia' => $_POST['MaTacGia'] ?: null,
                'MaTheLoai' => $_POST['MaTheLoai'] ?: null,
                'NhaXuatBan' => $_POST['NhaXuatBan'],
                'NamXuatBan' => $_POST['NamXuatBan'],
                'ISBN' => $_POST['ISBN'],
                'SoLuong' => $_POST['SoLuong'],
                'ViTriKe' => $_POST['ViTriKe'],
                'MoTa' => $_POST['MoTa'],
                'AnhBia' => $anhBia
            ];

            $this->sachModel->create($data);
            $this->redirect('index.php?controller=sach&action=index&msg=added');
        }

        $this->render('sach/them_sach', [
            'theloais' => $this->theLoaiModel->getAll(),
            'tacgias' => $this->tacGiaModel->getAll()
        ]);
    }

    public function sua() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $sach = $this->sachModel->getById($id);

        if (!$sach) {
            $this->redirect('index.php?controller=sach&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $anhBia = $sach['AnhBia'];
            if (isset($_FILES['AnhBia']) && $_FILES['AnhBia']['error'] === 0) {
                $anhBia = 'public/images/' . time() . '_' . $_FILES['AnhBia']['name'];
                move_uploaded_file($_FILES['AnhBia']['tmp_name'], $anhBia);
            }

            $data = [
                'TenSach' => $_POST['TenSach'],
                'MaTacGia' => $_POST['MaTacGia'] ?: null,
                'MaTheLoai' => $_POST['MaTheLoai'] ?: null,
                'NhaXuatBan' => $_POST['NhaXuatBan'],
                'NamXuatBan' => $_POST['NamXuatBan'],
                'ISBN' => $_POST['ISBN'],
                'SoLuong' => $_POST['SoLuong'],
                'ViTriKe' => $_POST['ViTriKe'],
                'MoTa' => $_POST['MoTa'],
                'AnhBia' => $anhBia
            ];

            $this->sachModel->update($id, $data);
            $this->redirect('index.php?controller=sach&action=index&msg=updated');
        }

        $this->render('sach/sua_sach', [
            'sach' => $sach,
            'theloais' => $this->theLoaiModel->getAll(),
            'tacgias' => $this->tacGiaModel->getAll()
        ]);
    }

    public function xoa() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $this->sachModel->delete($id);
        $this->redirect('index.php?controller=sach&action=index&msg=deleted');
    }

    public function chitiet() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $sach = $this->sachModel->getById($id);
        if (!$sach) {
            $this->redirect('index.php?controller=sach&action=index');
        }
        $this->render('sach/chi_tiet_sach', ['sach' => $sach]);
    }

    public function live_search() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $sachList = $this->sachModel->getAll($keyword);
        $role = isset($_SESSION['user']) ? $_SESSION['user']['VaiTro'] : 'USER';

        if (empty($sachList)) {
            echo '<tr><td colspan="8" class="text-center py-4 text-muted">Không tìm thấy sách nào phù hợp.</td></tr>';
            exit();
        }

        foreach ($sachList as $s) {
            $anhBia = $s['AnhBia'] ? '<img src="'.$s['AnhBia'].'" width="50" height="70" class="rounded shadow-sm" style="object-fit: cover;">' : '<div class="bg-secondary text-white rounded shadow-sm text-center d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;"><i class="fas fa-image"></i></div>';
            
            $badgeClass = $s['SoLuong'] > 0 ? 'bg-success' : 'bg-danger';
            $tenTacGia = htmlspecialchars($s['TenTacGia'] ?? 'N/A');
            $tenTheLoai = htmlspecialchars($s['TenTheLoai'] ?? 'N/A');
            $tenSach = htmlspecialchars($s['TenSach']);
            $viTri = htmlspecialchars($s['ViTriKe']);

            $actions = '<a href="index.php?controller=sach&action=chitiet&id='.$s['MaSach'].'" class="btn btn-sm btn-info text-white me-1" title="Chi Tiết"><i class="fas fa-eye"></i></a>';
            
            if ($role != 'USER') {
                $actions .= '<a href="index.php?controller=sach&action=sua&id='.$s['MaSach'].'" class="btn btn-sm btn-warning me-1" title="Sửa"><i class="fas fa-edit"></i></a>';
                $actions .= '<a href="index.php?controller=sach&action=xoa&id='.$s['MaSach'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\');" title="Xóa"><i class="fas fa-trash"></i></a>';
            }

            echo '<tr class="align-middle">';
            echo '<td class="fw-bold text-secondary">#'.$s['MaSach'].'</td>';
            echo '<td>'.$anhBia.'</td>';
            echo '<td class="fw-bold text-dark">'.$tenSach.'</td>';
            echo '<td><i class="fas fa-pen-nib text-muted me-1 small"></i> '.$tenTacGia.'</td>';
            echo '<td><span class="badge bg-light text-dark border">'.$tenTheLoai.'</span></td>';
            echo '<td><span class="badge rounded-pill '.$badgeClass.'">'.$s['SoLuong'].' cuốn</span></td>';
            echo '<td><i class="fas fa-map-marker-alt text-danger me-1 small"></i> '.$viTri.'</td>';
            echo '<td>'.$actions.'</td>';
            echo '</tr>';
        }
        exit();
    }
}
?>
