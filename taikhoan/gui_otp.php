<?php
include __DIR__ . '/../includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Thiếu email']);
        exit;
    }

    $response = callAPI("guiOTP", "POST", ['email' => $email], 'form');

    echo json_encode([
        'success' => isset($response['message']),
        'message' => $response['message'] ?? 'Gửi OTP thất bại'
    ]);
}
