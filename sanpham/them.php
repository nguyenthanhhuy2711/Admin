<?php
require_once __DIR__ . '/../includes/connect.php'; // Gồm cả hàm callAPI()

// Hiện lỗi chi tiết nếu có (chỉ nên dùng trong dev)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nếu người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $ten = $_POST['ten_san_pham'] ?? '';
    $moTa = $_POST['mo_ta'] ?? '';
    $gia = $_POST['gia'] ?? '';
    $maDanhMuc = $_POST['ma_danh_muc'] ?? '';

    // Kiểm tra ảnh
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];

        // Gửi dữ liệu tới API
        $url = "https://cuddly-exotic-snake.ngrok-free.app/themSanPham"
            . "?ten_san_pham=" . urlencode($ten)
            . "&mo_ta=" . urlencode($moTa)
            . "&gia=" . urlencode($gia)
            . "&ma_danh_muc=" . urlencode($maDanhMuc);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'file' => new CURLFile($fileTmp, $fileType, $fileName)
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "<script>
                sessionStorage.setItem('toastError', 'Lỗi khi gọi API: " . addslashes($err) . "');
                window.location.href = '/admin/index.php?page=sanpham';
            </script>";
            exit;
        }

        $result = json_decode($response, true);

        if (isset($result['ma_san_pham'])) {
            // ✅ Thành công
            echo "<script>
                sessionStorage.setItem('toastSuccess', 'Đã thêm sản phẩm thành công');
                window.location.href = '/admin/index.php?page=sanpham';
            </script>";
            exit;
        } else {
            $errMsg = $result['detail'] ?? 'Không thêm được sản phẩm';
            echo "<script>
                sessionStorage.setItem('toastError', " . json_encode($errMsg) . ");
                window.location.href = '/admin/index.php?page=sanpham';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            sessionStorage.setItem('toastError', 'Bạn chưa chọn ảnh hợp lệ.');
            window.location.href = '/admin/index.php?page=sanpham';
        </script>";
        exit;
    }
} else {
    echo "<script>
        sessionStorage.setItem('toastError', 'Truy cập không hợp lệ.');
        window.location.href = '/admin/index.php?page=sanpham';
    </script>";
    exit;
}
