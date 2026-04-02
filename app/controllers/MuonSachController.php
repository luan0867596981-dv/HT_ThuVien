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
            $sachList = $_POST['MaSach'] ?? []; // This should be MaCuonSach in the new UI

            if (empty($maDocGia) || empty($hanTra) || empty($sachList)) {
                $error = "Vui lòng nhập đầy đủ thông tin và chọn ít nhất 1 sách.";
            } else {
                try {
                    $data = [
                        'MaDocGia' => $maDocGia,
                        'HanTra' => $hanTra,
                        'sachList' => $sachList,
                        'MaChiNhanh' => 1 // Default branch for this lab
                    ];
                    
                    if ($this->muonSachModel->lapPhieuMuon($data)) {
                        $this->redirect('index.php?controller=muonsach&action=index&msg=created');
                    }
                } catch (Exception $e) {
                    if ($e->getMessage() == "ACCOUNT_BLOCKED_VIOLATION") {
                        $error = "Tài khoản đang bị khóa do vi phạm (Quá hạn chưa trả).";
                    } else {
                        $error = "Giao dịch thất bại: " . $e->getMessage();
                    }
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

    /**
     * READER FEATURE: ĐĂNG KÝ MƯỢN / ĐẶT TRƯỚC (Pre-order Title)
     */
    public function dat_truoc() {
        if (!isset($_GET['id'])) {
            $this->redirect('index.php?controller=sach&action=index');
        }

        $maDauSach = $_GET['id'];
        $maTaiKhoan = $_SESSION['user']['MaTaiKhoan'];

        // Lay MaDocGia tu MaTaiKhoan
        $docGiaModel = new DocGiaModel();
        $me = $docGiaModel->getByTaiKhoan($maTaiKhoan);

        if (!$me) {
            $this->redirect('index.php?controller=trangchu&msg=error_no_reader_record');
        }

        $result = $this->muonSachModel->datTruocDauSach($maDauSach, $me['MaDocGia']);

        if ($result === true) {
            $this->redirect('index.php?controller=muonsach&action=lich_su&msg=requested');
        } elseif ($result === "ACCOUNT_BANNED") {
            $this->redirect('index.php?controller=muonsach&action=lich_su&msg=account_banned');
        } else {
            $this->redirect('index.php?controller=muonsach&action=lich_su&msg=error');
        }
    }

    /**
     * READER FEATURE: LỊCH SỬ MƯỢN CỦA TÔI
     */
    public function lich_su() {
        $docGiaModel = new DocGiaModel();
        $me = $docGiaModel->getByTaiKhoan($_SESSION['user']['MaTaiKhoan']);
        
        $myLoans = $this->muonSachModel->getPhieuByDocGia($me['MaDocGia']);
        
        $this->render('muonsach/lich_su_muon', [
            'myLoans' => $myLoans
        ]);
    }

    /**
     * STAFF FEATURE: DANH SÁCH YÊU CẦU MƯỢN ĐANG CHỜ
     */
    public function yeu_cau() {
        if ($_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        $pendingRequests = $this->muonSachModel->getPhieuChoDuyet();
        
        $this->render('muonsach/yeu_cau_muon', [
            'pendingRequests' => $pendingRequests
        ]);
    }

    /**
     * STAFF FEATURE: PHÊ DUYỆT YÊU CẦU (Assign physical book)
     */
    public function duyet() {
        if ($_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $maPhieuMuon = $_POST['MaPhieuMuon'];
            $maCuonSach = $_POST['MaCuonSach'];
            $maNhanVien = $_SESSION['user']['MaTaiKhoan'];

            if ($this->muonSachModel->duyetPhieuMuon($maPhieuMuon, $maCuonSach, $maNhanVien)) {
                $this->redirect('index.php?controller=muonsach&action=yeu_cau&msg=approved');
            } else {
                $this->redirect('index.php?controller=muonsach&action=yeu_cau&msg=failed');
            }
        }
    }

    public function tu_choi() {
        if ($_SESSION['user']['VaiTro'] == 'USER') {
            $this->redirect('index.php?controller=trangchu');
        }

        if (isset($_GET['id'])) {
            $this->muonSachModel->tuChoiPhieuMuon($_GET['id']);
            $this->redirect('index.php?controller=muonsach&action=yeu_cau&msg=rejected');
        }
    }
}
?>
