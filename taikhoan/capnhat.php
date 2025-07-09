<?php
include __DIR__ . '/../includes/connect.php';

// Trả kết quả dưới dạng JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $data = [
        'ma_nguoi_dung' => $_POST['ma_nguoi_dung'],
        'ten_nguoi_dung' => $_POST['ten_nguoi_dung'],
        'email' => $_POST['email'],
        'mat_khau' => $_POST['mat_khau'], // Có thể rỗng
        'sdt' => $_POST['sdt'],
        'dia_chi_mac_dinh' => $_POST['dia_chi_mac_dinh'],
        'vai_tro' => $_POST['vai_tro'],
    ];

    // Gọi API FastAPI để cập nhật (dùng method POST, gửi dạng form)
    $result = callAPI('capnhatNguoiDung', 'POST', $data, 'form');

    // Kiểm tra và trả kết quả
    if (isset($result['message'])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['detail'] ?? 'Cập nhật thất bại'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ'
    ]);
}
