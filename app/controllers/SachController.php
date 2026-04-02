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
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?controller=taikhoan&action=dangnhap');
        }
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sachList = $this->sachModel->getAll($search);
        
        $this->render('sach/danh_sach_sach', [
            'sachList' => $sachList,
            'search' => $search
        ]);
    }

    public function timkiem() {
        $this->index();
    }

    public function them() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['VaiTro'] != 'ADMIN' && $_SESSION['user']['VaiTro'] != 'LIBRARIAN')) {
            $this->redirect('index.php?controller=trangchu');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $anhBia = '';
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

            $this->sachModel->create($data);
            $this->redirect('index.php?controller=sach&action=index&msg=added');
        }

        $this->render('sach/them_sach', [
            'theloais' => $this->theLoaiModel->getAll(),
            'tacgias' => $this->tacGiaModel->getAll()
        ]);
    }

    public function sua() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['VaiTro'] != 'ADMIN' && $_SESSION['user']['VaiTro'] != 'LIBRARIAN')) {
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
        if (!isset($_SESSION['user']) || $_SESSION['user']['VaiTro'] != 'ADMIN') {
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
            echo '<tr><td colspan="8" class="text-center py-5 text-muted fw-bold">Không tìm thấy tài sản nào phù hợp với tham số hiện tại.</td></tr>';
            exit();
        }

        foreach ($sachList as $s) {
            $maId = $s['MaDauSach'] ?? 0;
            $uid = str_pad($maId, 4, '0', STR_PAD_LEFT);
            $anhBia = $s['AnhBia'] ? '<img src="'.$s['AnhBia'].'" width="44" height="60" class="rounded-2 shadow-sm border" style="object-fit: cover;">' : '<div class="bg-slate-100 rounded-2 text-slate-400 d-flex align-items-center justify-content-center border" style="width: 44px; height: 60px;"><i class="fa-solid fa-box-archive"></i></div>';
            
            $stockBadge = '<span class="badge-inventory-vip"><i class="fa-solid fa-cubes"></i> '.$s['SoLuong'].'</span>';
            $catBadge = '<span class="badge-category-vip">'.htmlspecialchars($s['TenTheLoai'] ?? 'GENERAL').'</span>';
            $tenTacGia = htmlspecialchars($s['TenTacGia'] ?? 'N/A');
            $tenSach = htmlspecialchars($s['TenSach']);
            $isbn = htmlspecialchars($s['ISBN'] ?: 'NON-INDEXED');
            $viTri = !empty($s['ViTriKe']) ? htmlspecialchars($s['ViTriKe']) : '<span class="text-muted">Chưa xếp kệ</span>';

            $actions = '<div class="d-flex justify-content-end gap-2">';
            $actions .= '<a href="index.php?controller=sach&action=chitiet&id='.$maId.'" class="btn-action-vip" title="Inspect Node"><i class="fa-solid fa-magnifying-glass-plus"></i></a>';
            
            if ($role != 'USER') {
                $actions .= '<a href="index.php?controller=sach&action=sua&id='.$maId.'" class="btn-action-vip text-warning" title="Edit Metadata"><i class="fa-solid fa-pen-nib"></i></a>';
                $actions .= '<a href="index.php?controller=sach&action=xoa&id='.$maId.'" class="btn-action-vip text-danger" onclick="return confirm(\'Initiate Asset Termination?\');" title="Archive Node"><i class="fa-solid fa-trash-can"></i></a>';
            }
            $actions .= '</div>';

            echo '<tr>';
            echo '<td class="ps-4"><span class="badge-id-vip">#'.$uid.'</span></td>';
            echo '<td>'.$anhBia.'</td>';
            echo '<td><div class="asset-title">'.$tenSach.'</div><div class="text-slate-500 small fw-bold">ISBN: '.$isbn.'</div></td>';
            echo '<td><div class="fw-700 text-slate-600 small">'.$tenTacGia.'</div></td>';
            echo '<td>'.$catBadge.'</td>';
            echo '<td class="text-center">'.$stockBadge.'</td>';
            echo '<td><span class="text-indigo-600 fw-800 small uppercase"><i class="fa-solid fa-location-dot me-1"></i> '.$viTri.'</span></td>';
            echo '<td class="pe-4 text-end">'.$actions.'</td>';
            echo '</tr>';
        }
        exit();
    }

    public function tracuu_ai() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php?controller=taikhoan&action=dangnhap');
        }
        $this->render('sach/tracuu_ai');
    }

    public function live_search_ai() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $sachList = $this->sachModel->getAll($keyword);

        if (empty($sachList)) {
            echo '<div class="col-12"><div class="alert alert-info border text-center py-4 bg-white shadow-sm rounded-4">
                    <i class="fa-solid fa-robot d-block mb-3 fs-1 text-slate-300"></i>
                    Không tìm thấy kết quả phù hợp bằng AI. 
                    <br><span class="fw-bold text-indigo-600">Đề xuất:</span> Hãy thử tìm kiếm bằng từ khóa ngắn gọn hơn.
                  </div></div>';
            exit();
        }

        foreach ($sachList as $s) {
            $viTri = !empty($s['ViTriKe']) ? htmlspecialchars($s['ViTriKe']) : '<span class="text-muted">Chưa xếp kệ</span>';
            $tenSach = htmlspecialchars($s['TenSach']);
            $tenTacGia = htmlspecialchars($s['TenTacGia'] ?? 'N/A');
            
            echo '<div class="card-ai-result">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-indigo-100 rounded-3 p-3 text-indigo-600"><i class="fa-solid fa-book fa-lg"></i></div>
                        <div>
                            <h5 class="mb-0 fw-800 text-slate-800">'.$tenSach.'</h5>
                            <small class="text-slate-500 fw-bold">Tác giả: '.$tenTacGia.'</small>
                        </div>
                    </div>
                    <div class="badge-shelf-ai">
                        <i class="fa-solid fa-location-dot"></i> Vị trí kệ: '.$viTri.'
                    </div>
                  </div>';
        }
        exit();
    }
    public function exportToCSV() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['VaiTro'] != 'ADMIN' && $_SESSION['user']['VaiTro'] != 'LIBRARIAN')) {
            $this->redirect('index.php?controller=trangchu');
        }

        $sachList = $this->sachModel->getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="libsaas_inventory_'.date('Ymd').'.csv"');

        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8 support in Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header
        fputcsv($output, ['Mã Sách', 'Tên Sách', 'Tác Giả', 'Thể Loại', 'Nhà Xuất Bản', 'Năm XB', 'ISBN', 'Tồn Kho']);

        // Data
        foreach ($sachList as $s) {
            fputcsv($output, [
                $s['MaDauSach'],
                $s['TenSach'],
                $s['TenTacGia'] ?? 'N/A',
                $s['TenTheLoai'] ?? 'N/A',
                $s['NhaXuatBan'],
                $s['NamXuatBan'],
                $s['ISBN'],
                $s['SoLuong']
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * SENIOR SYSTEM ARCHITECT: STATIC CACHING SYNC
     * Đồng bộ danh mục sách từ Database vào file bộ nhớ Chatbot (.txt)
     */
    public function sync_chatbot() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['VaiTro'] != 'ADMIN' && $_SESSION['user']['VaiTro'] != 'LIBRARIAN')) {
            $this->redirect('index.php?controller=trangchu');
        }

        // BƯỚC 1: CHUẨN BỊ DỮ LIỆU TĨNH (FAQ) - Đảm bảo tính nhất quán nghiệp vụ
        $txtContent = "FAQ | quy trình, cách mượn | 📖 **Quy trình mượn:** 1. Tra cứu. 2. Lấy sách. 3. Quét mã tại quầy.\n";
        $txtContent .= "FAQ | quy định, mượn sách, tối đa | 📚 **Quy định mượn:** Tối đa 03 cuốn/lần. Hạn 14 ngày.\n";
        $txtContent .= "FAQ | trễ hạn, phạt, mất sách, rách | ⚠️ **Xử phạt:** Trễ hạn 5.000đ/ngày. Mất/hỏng đền 100% giá trị.\n";

        // BƯỚC 2: KÉO DỮ LIỆU SÁCH TỪ DATABASE (High-fidelity Pull)
        $sachList = $this->sachModel->getAll(); // Sử dụng phương thức getAll có sẵn đã JOIN TacGia
        $count = 0;

        // BƯỚC 3: XỬ LÝ CHUỖI VÀ ÉP VÀO FORMAT CỦA CHATBOT
        foreach ($sachList as $row) {
            $keywords = mb_strtolower($row['TenSach'], 'UTF-8');
            $tenSach = $row['TenSach'];
            $tacGia = $row['TenTacGia'] ?? 'N/A';
            $soLuong = $row['SoLuong'];
            $viTri = $row['ViTriKe'] ?: 'Chưa xếp kệ';
            $maId = $row['MaDauSach'];

            // Nối chuỗi theo định dạng pipe-delimited cho Chatbot Engine
            $txtContent .= "SACH | " . $keywords . " | 🏷️ **" . $tenSach . "** - Tác giả: " . $tacGia . " (Còn " . $soLuong . " cuốn tại " . $viTri . ") - <a href='index.php?controller=sach&action=chitiet&id=" . $maId . "' target='_blank'>Xem chi tiết</a>\n";
            $count++;
        }

        // BƯỚC 4: GHI FILE TỰ ĐỘNG (Atomic Persistence)
        $filePath = 'app/data/chatbot_data.txt';
        $result = file_put_contents($filePath, $txtContent);

        if ($result !== false) {
            $msg = "Đã đồng bộ thành công " . $count . " cuốn sách vào file bộ nhớ của Chatbot!";
            $this->redirect('index.php?controller=sach&action=index&msg=' . urlencode($msg));
        } else {
            $this->redirect('index.php?controller=sach&action=index&msg=Lỗi: Không thể ghi file tri thức!');
        }
    }
}
?>
