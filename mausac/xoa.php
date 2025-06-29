<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$baseUrl = "https://cuddly-exotic-snake.ngrok-free.app";

$ma_mau = $_GET['ma_mau'] ?? null;

if (!$ma_mau) {
    echo "<script>
        sessionStorage.setItem('toastError', 'Thiếu mã màu');
        window.location.href = '../index.php?page=mausac';
    </script>";
    exit;
}

$url = $baseUrl . "/xoaMauSac?ma_mau=" . urlencode($ma_mau);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
curl_close($ch);

$data = json_decode($res, true);
$msg = $data["message"] ?? null;
$err = $data["detail"] ?? null;

if ($msg) {
    echo "<script>
        sessionStorage.setItem('toastSuccess', " . json_encode($msg) . ");
        window.location.href = '../index.php?page=mausac';
    </script>";
} else {
    echo "<script>
        sessionStorage.setItem('toastError', " . json_encode($err ?? 'Xoá thất bại') . ");
        window.location.href = '../index.php?page=mausac';
    </script>";
}
exit;
