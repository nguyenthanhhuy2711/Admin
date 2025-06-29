<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

// Chỉ chấp nhận phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Phương thức không hợp lệ"]);
    exit;
}

$ma_nguoi_dung = $_POST['ma_nguoi_dung'] ?? null;

if (!$ma_nguoi_dung) {
    echo json_encode(["success" => false, "message" => "Thiếu mã người dùng"]);
    exit;
}

// Gọi API FastAPI xóa
$response = callAPI('xoaUser?ma_nguoi_dung=' . $ma_nguoi_dung, 'DELETE');

// Trả kết quả
if (!empty($response['success'])) {
    echo json_encode(["success" => true, "message" => $response['message'] ?? "Đã xoá thành công"]);
} else {
    echo json_encode(["success" => false, "message" => $response['message'] ?? "Xoá thất bại"]);
}
