<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten_danh_muc'] ?? '';

    $response = callAPI("themDanhMuc?ten_danh_muc=" . urlencode($ten), "POST");

    if (isset($response["message"])) {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Thêm danh mục thành công');
            window.location.href = '../index.php?page=danhmuc';
        </script>";
        exit;
    } else {
        $err = $response["detail"] ?? "Thêm danh mục thất bại";
        echo "<script>
            sessionStorage.setItem('toastError', " . json_encode($err) . ");
            window.location.href = '../index.php?page=danhmuc';
        </script>";
        exit;
    }
}
