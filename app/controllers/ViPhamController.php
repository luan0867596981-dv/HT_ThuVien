<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/ViPhamModel.php';

class ViPhamController extends BaseController {
    
    private $viPhamModel;

    public function __construct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }
        $this->viPhamModel = new ViPhamModel();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $viPhams = $this->viPhamModel->getAll($search);
        
        $this->render('vipham/danh_sach_vi_pham', [
            'viPhams' => $viPhams,
            'search' => $search
        ]);
    }

    public function thanh_toan() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($this->viPhamModel->thanhToan($id)) {
            $this->redirect('index.php?controller=vipham&action=index&msg=paid');
        } else {
            $this->redirect('index.php?controller=vipham&action=index&msg=error');
        }
    }

    public function gui_email() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        // Mocking email logic
        $this->redirect('index.php?controller=vipham&action=index&msg=emailed');
    }

    public function khoa_quyen() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($this->viPhamModel->khoaQuyenDocGia($id)) {
            $this->redirect('index.php?controller=vipham&action=index&msg=blocked');
        } else {
            $this->redirect('index.php?controller=vipham&action=index&msg=error');
        }
    }
}
?>
