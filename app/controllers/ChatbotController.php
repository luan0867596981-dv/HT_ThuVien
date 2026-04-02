<?php
require_once 'app/controllers/BaseController.php';

class ChatbotController extends BaseController {
    
    /**
     * LIBSAAS VIP AI BRAIN v2.0
     * Xử lý theo quy trình: Chuẩn hóa -> Intent -> Fuzzy Match -> Phản hồi
     */
    public function ask() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Lỗi: Không được phép truy cập trực tiếp.";
            return;
        }

        $rawInput = isset($_POST['message']) ? $_POST['message'] : '';
        if (empty(trim($rawInput))) {
            echo "👋 Chào bạn! Mình là trợ lý thư viện. Bạn cần tìm sách gì?";
            return;
        }

        // BƯỚC 1: CHUẨN HÓA INPUT
        $normalizedInput = $this->normalizeText($rawInput);

        // BƯỚC 2: NHẬN DIỆN Ý ĐỊNH (INTENT)
        if ($this->containsAny($normalizedInput, ['xin chao', 'hello', 'hi', 'chao'])) {
            echo "👋 Chào bạn! Mình là trợ lý thư viện LibSaaS VIP. Bạn cần tìm sách hay hỏi về nội quy nào?";
            return;
        }

        // Đọc dữ liệu từ file tri thức
        $filePath = 'app/data/chatbot_data.txt';
        $lines = file_exists($filePath) ? file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

        // Kiểm tra FAQ cao nhất (Quy trình, Quy định, Phạt)
        if ($this->containsAny($normalizedInput, ['muon', 'quy trinh', 'cach muon'])) {
            echo $this->findLineByCategory($lines, 'FAQ', 'quy trình'); return;
        }
        if ($this->containsAny($normalizedInput, ['toi da', 'bao nhieu cuon', 'quy dinh'])) {
            echo $this->findLineByCategory($lines, 'FAQ', 'quy định'); return;
        }
        if ($this->containsAny($normalizedInput, ['phat', 'tre', 'mat', 'rach'])) {
            echo $this->findLineByCategory($lines, 'FAQ', 'xử phạt'); return;
        }

        // BƯỚC 3: TÌM SÁCH (FUZZY MATCH >= 60%)
        $matches = [];
        $userWords = explode(' ', $normalizedInput);
        
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if (count($parts) < 3) continue;

            $category = trim($parts[0]);
            if ($category !== 'SACH') continue;

            $keywordsStr = trim($parts[1]); // Dữ liệu trong file thường là tiếng Việt có dấu
            $response = trim($parts[2]);
            
            // Chuẩn hóa từ khóa trong file để so sánh
            $normalizedKeywords = $this->normalizeText($keywordsStr);
            $keywordArray = explode(',', $normalizedKeywords);
            
            // Tính toán tỷ lệ khớp
            $matchCount = 0;
            $allBookKeywords = [];
            foreach($keywordArray as $k) {
                $subWords = explode(' ', trim($k));
                $allBookKeywords = array_merge($allBookKeywords, $subWords);
            }
            $allBookKeywords = array_unique($allBookKeywords);
            
            foreach ($userWords as $uw) {
                if (in_array($uw, $allBookKeywords)) {
                    $matchCount++;
                }
            }

            $totalWords = count($allBookKeywords);
            $ratio = ($totalWords > 0) ? ($matchCount / $totalWords) : 0;

            if ($ratio >= 0.6) {
                $matches[] = [
                    'response' => $response,
                    'ratio' => $ratio,
                    'length' => mb_strlen($keywordsStr)
                ];
            }
        }

        // BƯỚC 4: TRẢ KẾT QUẢ
        if (!empty($matches)) {
            // ƯU TIÊN: Ratio cao hơn -> Tên dài hơn
            usort($matches, function($a, $b) {
                if ($a['ratio'] == $b['ratio']) return $b['length'] - $a['length'];
                return $b['ratio'] > $a['ratio'] ? 1 : -1;
            });

            if (count($matches) === 1) {
                echo $matches[0]['response'];
            } else {
                $output = "Dạ, mình thấy có vài sách phù hợp:<br>";
                $count = 0;
                foreach ($matches as $m) {
                    $output .= $m['response'] . "<br>";
                    if (++$count >= 3) break; // Chỉ hiện tối đa 3 kết quả
                }
                echo $output;
            }
            return;
        }

        // BƯỚC 5: FALLBACK
        echo "Dạ thư viện chưa có thông tin về sách này, bạn thử từ khóa khác nhé! 😅";
    }

    /**
     * CHUẨN HÓA VĂN BẢN (Lowercase, No Accent, No Stopwords)
     */
    private function normalizeText($text) {
        $text = mb_strtolower($text, 'UTF-8');
        $text = $this->removeTones($text);
        
        // Xóa ký tự đặc biệt
        $text = preg_replace('/[^\w\s]/u', '', $text);
        
        // Xóa từ dư (Stopwords)
        $stopWords = ['cho', 'toi', 'minh', 'co', 'khong'];
        $words = explode(' ', $text);
        $filteredWords = array_diff($words, $stopWords);
        
        return trim(implode(' ', $filteredWords));
    }

    private function findLineByCategory($lines, $category, $subKey) {
        foreach ($lines as $line) {
            if (strpos($line, $category) !== false && mb_strpos(mb_strtolower($line), $subKey) !== false) {
                $parts = explode('|', $line);
                return isset($parts[2]) ? trim($parts[2]) : "Thông tin đang được cập nhật.";
            }
        }
        return "Dạ hiện tại thông tin này đang được cập nhật ạ.";
    }

    private function containsAny($str, array $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($str, $keyword) !== false) return true;
        }
        return false;
    }

    private function removeTones($str) {
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ì|Ỉ|Ĩ|Ị',
        );
        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }
}
?>