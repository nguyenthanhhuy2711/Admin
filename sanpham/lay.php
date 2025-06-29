<?php
header("Content-Type: application/json; charset=UTF-8");
require __DIR__ . '/../includes/connect.php';

$maSanPham = $_GET['maSanPham'] ?? null;

if (!$maSanPham) {
    echo json_encode(["success" => false, "message" => "Thiếu mã sản phẩm"]);
    exit;
}

// Gọi API GET /getSanPham/{maSanPham}
$response = callAPI("getSanPham/" . $maSanPham);

if ($response && is_array($response) && isset($response['ma_san_pham'])) {
    echo json_encode(["success" => true, "data" => $response]);
} else {
    $message = $response['message'] ?? 'Không tìm thấy sản phẩm';
    echo json_encode(["success" => false, "message" => $message]);
}
