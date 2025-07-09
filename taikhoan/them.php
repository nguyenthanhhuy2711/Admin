<?php
include __DIR__ . '/../includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'] ?? null;
    $email = $_POST['email'] ?? null;

    if (!$otp || !$email) {
        echo json_encode(['success' => false, 'message' => 'Thiếu email hoặc mã OTP']);
        exit;
    }

    // B1: Xác thực OTP
    $otpRes = callAPI("xacThucOTP", "POST", ['email' => $email, 'otp' => $otp], 'form');

    if (!isset($otpRes['message']) || stripos($otpRes['message'], 'thành công') === false) {
        echo json_encode(['success' => false, 'message' => $otpRes['detail'] ?? 'Xác thực OTP thất bại']);
        exit;
    }

    // B2: Gửi thông tin tạo tài khoản
    $data = [
        'ten_nguoi_dung' => $_POST['ten_nguoi_dung'] ?? '',
        'email' => $email,
        'mat_khau' => $_POST['mat_khau'] ?? '',
        'sdt' => $_POST['sdt'] ?? '',
        'dia_chi_mac_dinh' => $_POST['dia_chi_mac_dinh'] ?? '',
        'vai_tro' => $_POST['vai_tro'] ?? 'user'
    ];

    $res = callAPI("themUser", "POST", $data, 'json');

    echo json_encode([
        'success' => isset($res['message']) && stripos($res['message'], 'thành công') !== false,
        'message' => $res['message'] ?? 'Tạo tài khoản thất bại'
    ]);
}
