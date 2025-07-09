<?php
require_once __DIR__ . '/../includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSanPham = $_POST['ma_san_pham'] ?? null;
    $tenSanPham = $_POST['ten_san_pham'] ?? '';
    $moTa = $_POST['mo_ta'] ?? '';
    $gia = $_POST['gia'] ?? 0;
    $maDanhMuc = $_POST['ma_danh_muc'] ?? '';
    $trangThai = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1; // ✅ Sửa tại đây

    if (!$maSanPham) {
        echo "<script>
            sessionStorage.setItem('toastError', 'Thiếu mã sản phẩm');
            window.location.href = '../index.php?page=sanpham';
        </script>";
        exit;
    }

    $apiUrl = "https://cuddly-exotic-snake.ngrok-free.app/suaSanPham";

    $postData = [
        'ma_san_pham' => $maSanPham,
        'ten_san_pham' => $tenSanPham,
        'mo_ta' => $moTa,
        'gia' => $gia,
        'ma_danh_muc' => $maDanhMuc,
        'trang_thai' => $trangThai,
        '_method' => 'PUT'
    ];

    // Gửi ảnh nếu có
    if (!empty($_FILES['anh_dai_dien']['tmp_name']) && is_uploaded_file($_FILES['anh_dai_dien']['tmp_name'])) {
        $tmpName = $_FILES['anh_dai_dien']['tmp_name'];
        $filename = $_FILES['anh_dai_dien']['name'];
        $postData['file'] = new CURLFile($tmpName, mime_content_type($tmpName), $filename);
    }

    // Gửi API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        echo "<script>
            sessionStorage.setItem('toastError', 'Lỗi khi gọi API: " . addslashes($error) . "');
            window.location.href = '../index.php?page=sanpham';
        </script>";
        exit;
    }

    $result = json_decode($response, true);

    if (isset($result['message'])) {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Cập nhật sản phẩm thành công');
            window.location.href = '../index.php?page=sanpham';
        </script>";
    } else {
        $err = $result['detail'] ?? 'Cập nhật thất bại';
        echo "<script>
            sessionStorage.setItem('toastError', " . json_encode($err) . ");
            window.location.href = '../index.php?page=sanpham';
        </script>";
    }
    exit;
} else {
    echo "<script>
        sessionStorage.setItem('toastError', 'Truy cập không hợp lệ');
        window.location.href = '../index.php?page=sanpham';
    </script>";
    exit;
}
