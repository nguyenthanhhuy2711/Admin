<?php
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID']);
    exit;
}

$response = callAPI("api/voucher/$id");

if (!$response || !isset($response['voucher'])) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu voucher']);
    exit;
}

echo json_encode([
    'success' => true,
    'voucher' => $response['voucher'],
    'ds_nguoi_dung' => $response['ds_nguoi_dung'] ?? []
]);
