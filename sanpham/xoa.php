<?php
require_once __DIR__ . '/../includes/connect.php';

if (!isset($_GET['id'])) {
    echo "<script>
        sessionStorage.setItem('toastError', 'Thiếu mã sản phẩm');
        window.location.href = '../index.php?page=sanpham';
    </script>";
    exit;
}

$maSanPham = $_GET['id'];

// Gọi API xóa sản phẩm
$url = "https://cuddly-exotic-snake.ngrok-free.app/xoaSanPham?ma_san_pham=" . urlencode($maSanPham);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_CUSTOMREQUEST => "DELETE",
    CURLOPT_RETURNTRANSFER => true,
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "<script>
        sessionStorage.setItem('toastError', 'Lỗi khi gọi API: " . addslashes($err) . "');
        window.location.href = '../index.php?page=sanpham';
    </script>";
    exit;
}

$result = json_decode($response, true);

if (isset($result['message'])) {
    echo "<script>
        sessionStorage.setItem('toastSuccess', 'Đã xoá sản phẩm thành công');
        window.location.href = '../index.php?page=sanpham';
    </script>";
    exit;
} else {
    $errMsg = $result['detail'] ?? 'Không xoá được sản phẩm';
    echo "<script>
        sessionStorage.setItem('toastError', " . json_encode($errMsg) . ");
        window.location.href = '../index.php?page=sanpham';
    </script>";
    exit;
}
