<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/MuonSachModel.php';
require_once 'app/models/DocGiaModel.php';
require_once 'app/models/SachModel.php';

class MuonSachController extends BaseController {
    
    private $muonSachModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?controller=taikhoan&action=dangnhap');
        }
        $this->muonSachModel = new MuonSachModel();
    }

    public function index() {
        if ($_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $phieuMuons = $this->muonSachModel->getAll($search);

        foreach ($phieuMuons as &$pm) {
            $pm['ChiTiet'] = $this->muonSachModel->getChiTietPhieu($pm['MaPhieuMuon']);
        }

        $this->render('muonsach/danh_sach_muon', [
            'phieuMuons' => $phieuMuons,
            'search' => $search
        ]);
    }

    public function tao() {
        if ($_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        $docGiaModel = new DocGiaModel();
        $sachModel = new SachModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maDocGia = $_POST['MaDocGia'];
            $hanTra = $_POST['HanTra'];
            $sachList = $_POST['MaSach'] ?? [];

            if (empty($maDocGia) || empty($hanTra) || empty($sachList)) {
                $error = "Vui lòng nhập đầy đủ thông tin và chọn ít nhất 1 sách.";
            } else {
                if ($this->muonSachModel->lapPhieuMuon($maDocGia, $hanTra, $sachList)) {
                    $this->redirect('index.php?controller=muonsach&action=index&msg=created');
                } else {
                    $error = "Không thể tạo phiếu mượn. Vui lòng kiểm tra lại số lượng sách trong kho.";
                }
            }
        }

        $docgias = $docGiaModel->getAll();
        // Only load available books
        $allSach = $sachModel->getAll();
        $sachAvailable = array_filter($allSach, function($s) {
            return $s['SoLuong'] > 0;
        });

        $this->render('muonsach/lap_phieu_muon', [
            'docgias' => $docgias,
            'sachList' => $sachAvailable,
            'error' => $error ?? null
        ]);
    }

    // This could be for USER role
    public function dat_truoc() {
        // Mock feature where users click to request borrow
        echo "<script>alert('Tính năng đang được phát triển. Tạm thời vui lòng đến quầy!'); window.location.href='index.php?controller=sach&action=timkiem';</script>";
    }
}
?>
