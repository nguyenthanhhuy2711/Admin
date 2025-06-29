<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ'
    ]);
    exit;
}

$ten_nguoi_dung = $_POST['ten_nguoi_dung'] ?? '';
$email = $_POST['email'] ?? '';
$mat_khau = $_POST['mat_khau'] ?? '';
$sdt = $_POST['sdt'] ?? '';
$dia_chi_mac_dinh = $_POST['dia_chi_mac_dinh'] ?? '';
$vai_tro = $_POST['vai_tro'] ?? 'user';

if (!$ten_nguoi_dung || !$email || !$mat_khau || !$sdt) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập đầy đủ thông tin bắt buộc'
    ]);
    exit;
}

$data = [
    'ten_nguoi_dung' => $ten_nguoi_dung,
    'email' => $email,
    'mat_khau' => $mat_khau,
    'sdt' => $sdt,
    'dia_chi_mac_dinh' => $dia_chi_mac_dinh,
    'vai_tro' => $vai_tro
];

$response = callAPI('themUser', 'POST', $data, 'form');

if (!empty($response['ma_nguoi_dung'])) {
    echo json_encode([
        'success' => true,
        'message' => $response['message'] ?? 'Thêm người dùng thành công'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => $response['message'] ?? 'Thêm thất bại'
    ]);
}
