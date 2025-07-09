<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';
header('Content-Type: application/json');

// Đọc JSON từ body
$raw = file_get_contents('php://input');
file_put_contents(__DIR__ . '/log_input.txt', $raw);  // ✅ GHI LOG vào file để kiểm tra dữ liệu gửi từ JS

$input = json_decode($raw, true);

$ma_don_hang = $input['ma_don_hang'] ?? null;
$trang_thai_moi = $input['trang_thai_moi'] ?? null;

if (!$ma_don_hang || !$trang_thai_moi) {
    echo json_encode([
        "success" => false,
        "message" => "Thiếu thông tin",
        "debug" => [
            "ma_don_hang" => $ma_don_hang,
            "trang_thai_moi" => $trang_thai_moi
        ]
    ]);
    exit;
}

// Chuẩn bị dữ liệu gửi API
$data = [
    "ma_don_hang" => $ma_don_hang,
    "trang_thai_moi" => $trang_thai_moi
];

// Gọi API FastAPI
$response = callAPI("capNhatTrangThaiDonHang", "POST", $data);

// Phân tích kết quả trả về
if (!empty($response['message']) && str_contains($response['message'], 'thành công')) {
    echo json_encode([
        "success" => true,
        "message" => $response['message']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $response['detail'] ?? "Lỗi không xác định",
        "debug" => $response
    ]);
}
