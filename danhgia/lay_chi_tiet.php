<?php
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

$maSanPham = $_GET['ma_san_pham'] ?? null;

if (!$maSanPham) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã sản phẩm']);
    exit;
}

$data = callAPI("get/chiTietDanhGia?ma_san_pham=" . $maSanPham);

if ($data && isset($data['thong_tin'])) {
    echo json_encode([
        'success' => true,
        'thong_tin' => $data['thong_tin'],
        'danh_sach_danh_gia' => $data['danh_sach_danh_gia']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Không có dữ liệu hoặc lỗi API']);
}
