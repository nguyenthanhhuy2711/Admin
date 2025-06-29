<?php
include __DIR__ . '/../includes/connect.php';

$maDonHang = $_GET['ma_don_hang'] ?? null;

if (!$maDonHang) {
    echo "<p style='color:red;'>Không tìm thấy mã đơn hàng.</p>";
    exit;
}

$data = callAPI("getChiTietDonHang?ma_don_hang=$maDonHang");

if (empty($data)) {
    echo "<p style='color:red;'>Không có dữ liệu chi tiết đơn hàng.</p>";
    exit;
}

echo "<ul style='list-style:none;padding-left:0'>";
foreach ($data as $item) {
    echo "<li style='margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 10px;'>";
    echo "<img src='{$item['hinh_anh']}' style='height:70px; vertical-align:middle; border-radius:4px; margin-right:10px;' />";
    echo "<strong>{$item['ten_san_pham']}</strong><br>";
    echo "Màu: {$item['ten_mau']} | Size: {$item['kich_thuoc']} | Số lượng: {$item['so_luong']} | Giá: " . number_format($item['gia']) . " VND";
    echo "</li>";
}
echo "</ul>";
