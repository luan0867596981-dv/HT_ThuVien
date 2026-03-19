<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/TaiKhoanModel.php';

class TaiKhoanController extends BaseController {
    
    private $model;

    public function __construct() {
        $this->model = new TaiKhoanModel();
    }

    public function dangnhap() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->model->login($username, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                $this->redirect('index.php?controller=trangchu&action=index');
            } else {
                $data['error'] = 'Sai tên đăng nhập hoặc mật khẩu, hoặc tài khoản bị khóa.';
                $this->render('taikhoan/dangnhap', $data);
                return;
            }
        }
        $this->render('taikhoan/dangnhap');
    }

    public function dangky() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $data['error'] = 'Mật khẩu xác nhận không khớp.';
                $this->render('taikhoan/dangky', $data);
                return;
            }

            $success = $this->model->register([
                'TenDangNhap' => $_POST['username'],
                'MatKhau' => $_POST['password'],
                'HoTen' => $_POST['hoten'],
                'Email' => $_POST['email'],
                'DienThoai' => $_POST['dienthoai']
            ]);

            if ($success) {
                $this->redirect('index.php?controller=taikhoan&action=dangnhap&msg=registered');
            } else {
                $data['error'] = 'Đăng ký thất bại. Tên đăng nhập có thể đã bị trùng.';
                $this->render('taikhoan/dangky', $data);
                return;
            }
        }
        $this->render('taikhoan/dangky');
    }

    public function dangxuat() {
        session_destroy();
        $this->redirect('index.php?controller=taikhoan&action=dangnhap');
    }

    public function hoso() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?controller=taikhoan&action=dangnhap');
        }

        $profile = $this->model->getProfile($_SESSION['user']['MaTaiKhoan']);
        
        $this->render('taikhoan/hoso', ['profile' => $profile]);
    }

    // CRUD for Admin
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] != 'ADMIN') {
            $this->redirect('index.php?controller=trangchu');
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $taikhoans = $this->model->getAll($search);

        $this->render('taikhoan/danh_sach_tai_khoan', [
            'taikhoans' => $taikhoans,
            'search' => $search
        ]);
    }

    public function sua() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] != 'ADMIN') {
            $this->redirect('index.php?controller=trangchu');
        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $taikhoan = $this->model->getById($id);

        if (!$taikhoan) {
            $this->redirect('index.php?controller=taikhoan&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'VaiTro' => $_POST['VaiTro'],
                'TrangThai' => $_POST['TrangThai']
            ];

            if (!empty($_POST['mat_khau_moi'])) {
                $data['MatKhau'] = password_hash($_POST['mat_khau_moi'], PASSWORD_DEFAULT);
            }

            // Ngăn admin tự hạ quyền hoặc tự khóa mình
            if ($id == $_SESSION['user']['MaTaiKhoan']) {
                $data['VaiTro'] = 'ADMIN';
                $data['TrangThai'] = 'ACTIVE';
            }

            $this->model->update($id, $data);
            $this->redirect('index.php?controller=taikhoan&action=index&msg=updated');
        }

        $this->render('taikhoan/sua_tai_khoan', ['taikhoan' => $taikhoan]);
    }

    public function xoa() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] != 'ADMIN') {
            $this->redirect('index.php?controller=trangchu');
        }

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        if ($id != $_SESSION['user']['MaTaiKhoan']) { // Ngăn admin tự xóa mình
            $this->model->delete($id);
        }
        
        $this->redirect('index.php?controller=taikhoan&action=index&msg=deleted');
    }
}
?>
