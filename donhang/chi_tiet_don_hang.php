<?php
header('Content-Type: application/json');
include __DIR__ . '/../includes/connect.php';

$maDonHang = $_GET['ma_don_hang'] ?? null;

if (!$maDonHang) {
    echo json_encode(["error" => "Thiếu mã đơn hàng"]);
    exit;
}

$data = callAPI("getChiTietDonHang?ma_don_hang=$maDonHang");

if (empty($data) || empty($data['don_hang'])) {
    echo json_encode(["error" => "Không có dữ liệu đơn hàng"]);
    exit;
}

echo json_encode($data);
