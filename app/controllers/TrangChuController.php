<?php
require_once 'app/controllers/BaseController.php';
require_once 'app/models/ThongKeModel.php';

class TrangChuController extends BaseController {
    public function index() {
        // Require login
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?controller=taikhoan&action=dangnhap');
        }

        $thongKeModel = new ThongKeModel();

        $data = [
            'sach' => $thongKeModel->getTongSach(),
            'doc_gia' => $thongKeModel->getTongDocGia(),
            'dang_muon' => $thongKeModel->getTongDangMuon(),
            'qua_han' => $thongKeModel->getTongQuaHan()
        ];
        
        $this->render('trangchu/index', $data);
    }
}
?>
