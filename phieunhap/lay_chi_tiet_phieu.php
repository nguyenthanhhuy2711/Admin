<?php
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

if (!isset($_GET['ma_phieu_nhap']) || empty($_GET['ma_phieu_nhap'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu mã phiếu nhập']);
    exit;
}

$maPhieuNhap = urlencode(trim($_GET['ma_phieu_nhap']));
$result = callAPI("phieu-nhap/$maPhieuNhap", 'GET');

if (!empty($result)) {
    echo json_encode($result);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Không lấy được dữ liệu từ API']);
}
