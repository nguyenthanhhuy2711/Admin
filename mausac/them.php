<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_mau = trim($_POST['ten_mau'] ?? '');

    if (empty($ten_mau)) {
        echo "<script>
            sessionStorage.setItem('toastError', 'Tên màu không được để trống');
            window.location.href = '../index.php?page=mausac';
        </script>";
        exit;
    }

    $response = callAPI("addMauSac?ten_mau=" . urlencode($ten_mau) . "&ma_hex=%23000000", "POST");

    if (isset($response["message"])) {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Thêm màu thành công');
            window.location.href = '../index.php?page=mausac';
        </script>";
    } else {
        $error = $response["detail"] ?? "Thêm thất bại";
        echo "<script>
            sessionStorage.setItem('toastError', " . json_encode($error) . ");
            window.location.href = '../index.php?page=mausac';
        </script>";
    }
    exit;
}
