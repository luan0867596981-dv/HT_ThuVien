<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/TraSachModel.php';
require_once 'app/models/MuonSachModel.php';

class TraSachController extends BaseController {
    
    private $traSachModel;
    private $muonSachModel;

    public function __construct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }
        $this->traSachModel = new TraSachModel();
        $this->muonSachModel = new MuonSachModel();
    }

    public function index() {
        $phieuTras = $this->traSachModel->getPhieuTraHienTai();
        $this->render('trasach/danh_sach_tra', ['phieuTras' => $phieuTras]);
    }

    public function xac_nhan() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $phieuMuons = $this->muonSachModel->getAll($id);
        $phieuMuon = null;
        foreach($phieuMuons as $pm) {
            if($pm['MaPhieuMuon'] == $id) {
                $phieuMuon = $pm;
                $phieuMuon['ChiTiet'] = $this->muonSachModel->getChiTietPhieu($id);
                break;
            }
        }

        if (!$phieuMuon || $phieuMuon['TrangThai'] == 'DA_TRA') {
            $this->redirect('index.php?controller=muonsach&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tinhTrangSach = $_POST['TinhTrangSach'];
            $tienPhat = floatval($_POST['TienPhat']);

            if ($this->traSachModel->xacNhanTra($id, $tinhTrangSach, $tienPhat)) {
                $this->redirect('index.php?controller=trasach&action=index&msg=success');
            } else {
                $error = "Đã xảy ra lỗi khi xác nhận trả sách.";
                $this->render('trasach/xac_nhan_tra', ['phieuMuon' => $phieuMuon, 'error' => $error]);
                return;
            }
        }

        // Calculate late penalty if any
        $tienPhatMien = 0;
        $today = strtotime(date('Y-m-d'));
        $hanTra = strtotime(date('Y-m-d', strtotime($phieuMuon['HanTra'])));
        if ($today > $hanTra) {
            $days_late = floor(($today - $hanTra) / (60 * 60 * 24));
            $tienPhatMien = $days_late * 5000; // 5000 vnd per late day
        }

        $this->render('trasach/xac_nhan_tra', [
            'phieuMuon' => $phieuMuon,
            'tienPhatDuKien' => $tienPhatMien
        ]);
    }
}
?>
