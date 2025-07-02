<?php
include __DIR__ . '/../includes/connect.php';

$maSanPham = $_GET['maSanPham'] ?? null;

if (!$maSanPham) {
    echo json_encode([]);
    exit;
}

$result = callAPI("getMauTheoSanPham?maSanPham=$maSanPham");

header('Content-Type: application/json');
echo json_encode($result);
