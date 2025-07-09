<?php
require_once __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu hoặc sai mã người dùng']);
    exit;
}

// Gọi API FastAPI lấy thông tin người dùng
$result = callAPI("getUser?ma_nguoi_dung=" . intval($id), "GET");

if (isset($result['ma_nguoi_dung'])) {
    echo json_encode(['success' => true, 'data' => $result]);
} elseif (isset($result['message'])) {
    echo json_encode(['success' => false, 'message' => $result['message']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không lấy được thông tin người dùng']);
}
