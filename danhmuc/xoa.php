<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$ma = $_GET['id'] ?? '';

if ($ma) {
    $response = callAPI("xoaDanhMuc?ma_danh_muc=" . $ma, "DELETE");

    if (isset($response["message"])) {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Xoá danh mục thành công');
            window.location.href = '../index.php?page=danhmuc';
        </script>";
        exit;
    } else {
        $err = $response["detail"] ?? "Xoá danh mục thất bại";
        echo "<script>
            sessionStorage.setItem('toastError', " . json_encode($err) . ");
            window.location.href = '../index.php?page=danhmuc';
        </script>";
        exit;
    }
}
