<?php

include __DIR__ . '/../includes/connect.php';
include __DIR__ . '/../includes/check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? '';
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'] ?? '';
    $so_luong = $_POST['so_luong'] ?? '';

    $response = callAPI('capnhatThoiGianVoucher', 'POST', [
        'id' => $id,
        'ngay_bat_dau' => $ngay_bat_dau,
        'ngay_ket_thuc' => $ngay_ket_thuc,
        'so_luong' => $so_luong
    ]);

    if (isset($response['message'])) {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Cập nhật thành công');
            window.location.href = '/admin/index.php?page=voucher';
        </script>";
        exit;
    } else {
        $err = $response['detail'] ?? 'Cập nhật thất bại';
        echo "<script>
            sessionStorage.setItem('toastError', " . json_encode($err) . ");
            window.location.href = '/admin/index.php?page=voucher';
        </script>";
        exit;
    }
}
