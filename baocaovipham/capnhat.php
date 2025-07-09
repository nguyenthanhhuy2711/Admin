<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $trang_thai = $_POST['trang_thai'] ?? null;

    if ($id && $trang_thai) {
        $res = callAPI("capNhatTrangThaiToCao", "PUT", [
            "id" => (int)$id,
            "trang_thai" => $trang_thai
        ]);

        if ($res && !isset($res['detail'])) {
            echo json_encode(["success" => true, "message" => "Cập nhật trạng thái thành công."]);
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Cập nhật thất bại từ API."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Thiếu dữ liệu ID hoặc trạng thái."]);
        exit;
    }
}

echo json_encode(["success" => false, "message" => "Yêu cầu không hợp lệ."]);
