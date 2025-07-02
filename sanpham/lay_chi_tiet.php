<?php
header("Content-Type: application/json; charset=UTF-8");
require __DIR__ . '/../includes/connect.php';

$maSanPham = $_GET['ma_san_pham'] ?? null;

if (!$maSanPham) {
    echo json_encode(["success" => false, "message" => "Thiếu mã sản phẩm"]);
    exit;
}

// Gọi API getSanPham/{ma}
$sanPham = callAPI("getSanPham/" . $maSanPham);

// Gọi API getDanhSachAnhBienThe
$anhBienThe = callAPI("getDanhSachAnhBienThe?ma_san_pham=" . $maSanPham);

// Kiểm tra sản phẩm có tồn tại không
if ($sanPham && is_array($sanPham) && isset($sanPham['ma_san_pham'])) {
    echo json_encode([
        "success" => true,
        "san_pham" => $sanPham,
        "anh_bien_the" => $anhBienThe ?? []
    ]);
} else {
    $message = $sanPham['message'] ?? "Không tìm thấy sản phẩm";
    echo json_encode(["success" => false, "message" => $message]);
}
