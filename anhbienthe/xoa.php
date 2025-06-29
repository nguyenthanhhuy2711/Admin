<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$baseUrl = "https://cuddly-exotic-snake.ngrok-free.app";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ma_san_pham = $_GET['ma_san_pham'] ?? null;
    $ma_mau = $_GET['ma_mau'] ?? null;

    if ($ma_san_pham && $ma_mau) {
        $data = [
            'ma_san_pham' => $ma_san_pham,
            'ma_mau' => $ma_mau
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "$baseUrl/xoaAnhBienTheTheoSanPhamVaMau",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            echo "<script>
                sessionStorage.setItem('toastSuccess', 'Xoá ảnh biến thể thành công');
                window.location.href = '/admin/index.php?page=anhbienthe';
            </script>";
        } else {
            echo "<script>
                sessionStorage.setItem('toastError', 'Lỗi xoá ảnh biến thể');
                window.location.href = '/admin/index.php?page=anhbienthe';
            </script>";
        }
    } else {
        echo "<script>
            sessionStorage.setItem('toastError', 'Thiếu mã sản phẩm hoặc mã màu');
            window.location.href = '/admin/index.php?page=anhbienthe';
        </script>";
    }
}
