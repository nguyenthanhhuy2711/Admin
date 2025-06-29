<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

// Kiểm tra phương thức
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Phương thức không hợp lệ"]);
    exit;
}

// Lấy dữ liệu từ POST
$ma_don_hang = $_POST['ma_don_hang'] ?? null;
$trang_thai_moi = $_POST['trang_thai_moi'] ?? null;

if (!$ma_don_hang || !$trang_thai_moi) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin cần thiết"]);
    exit;
}

// Gửi dữ liệu sang API
$data = [
    "ma_don_hang" => (int)$ma_don_hang,
    "trang_thai_moi" => $trang_thai_moi
];

$response = callAPI("capNhatTrangThaiDonHang", "PUT", $data);

// Xử lý kết quả
if (!empty($response['message']) && str_contains($response['message'], 'thành công')) {
    echo json_encode(["success" => true, "message" => $response['message']]);
} else {
    echo json_encode(["success" => false, "message" => $response['detail'] ?? "Lỗi không xác định"]);
}
