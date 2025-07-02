<?php
require_once __DIR__ . '/../includes/check_login.php';
require_once __DIR__ . '/../includes/connect.php'; // chứa callAPI()

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
        sessionStorage.setItem('toastError', 'Phương thức không hợp lệ.');
        window.location.href = '../index.php?page=phieunhap';
    </script>";
    exit;
}

$nguoi_nhap = $_SESSION['admin_name'] ?? 'Không xác định';
$ngay_nhap = date('Y-m-d H:i:s');

$ma_san_pham = $_POST['ma_san_pham'] ?? [];
$ma_mau = $_POST['ma_mau'] ?? [];
$kich_thuoc = $_POST['kich_thuoc'] ?? [];
$so_luong_ton = $_POST['so_luong_ton'] ?? [];

$chi_tiet = [];

foreach ($ma_san_pham as $i => $maSP) {
    if (
        isset($maSP, $ma_mau[$i], $kich_thuoc[$i], $so_luong_ton[$i]) &&
        $maSP && $ma_mau[$i] && $kich_thuoc[$i] && $so_luong_ton[$i] > 0
    ) {
        $chi_tiet[] = [
            'ma_san_pham' => (int)$maSP,
            'ma_mau' => (int)$ma_mau[$i],
            'kich_thuoc' => $kich_thuoc[$i],
            'so_luong' => (int)$so_luong_ton[$i]
        ];
    }
}

if (empty($chi_tiet)) {
    echo "<script>
        sessionStorage.setItem('toastError', 'Không có dòng nhập kho hợp lệ.');
        window.location.href = '../index.php?page=phieunhap';
    </script>";
    exit;
}

$data = [
    'nguoi_nhap' => $nguoi_nhap,
    'ngay_nhap' => $ngay_nhap,
    'chi_tiet' => $chi_tiet
];

$response = callAPI('nhap-kho', 'POST', $data, 'json');

if (!empty($response['ma_phieu_nhap'])) {
    echo "<script>
        sessionStorage.setItem('toastSuccess', 'Nhập kho thành công!');
        window.location.href = '../index.php?page=phieunhap';
    </script>";
} else {
    $error = $response['detail'] ?? $response['message'] ?? 'Không thể nhập kho.';
    echo "<script>
        sessionStorage.setItem('toastError', " . json_encode($error) . ");
        window.location.href = '../index.php?page=phieunhap';
    </script>";
}
