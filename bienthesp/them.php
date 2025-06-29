<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ.'
    ]);
    exit;
}

// Lấy dữ liệu từ form
$ma_san_pham = $_POST['ma_san_pham'] ?? null;
$kich_thuoc = $_POST['kich_thuoc'] ?? null;
$ma_mau = $_POST['ma_mau'] ?? null;
$so_luong_ton = $_POST['so_luong_ton'] ?? null;

// Kiểm tra dữ liệu
if (!$ma_san_pham || !$kich_thuoc || !$ma_mau || !$so_luong_ton) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng điền đầy đủ thông tin.'
    ]);
    exit;
}

// Chuẩn bị dữ liệu gọi API
$data = [
    'ma_san_pham' => $ma_san_pham,
    'kich_thuoc' => $kich_thuoc,
    'ma_mau' => $ma_mau,
    'so_luong_ton' => $so_luong_ton
];

// Gọi API dạng form
$response = callAPI('themBienTheSanPham', 'POST', $data, 'form');

// Trả kết quả JSON cho fetch xử lý
if (isset($response['ma_bien_the'])) {
    echo json_encode([
        'success' => true,
        'message' => $response['message'] ?? 'Thêm thành công.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => $response['detail'] ?? $response['message'] ?? 'Không thể thêm biến thể sản phẩm.'
    ]);
}
