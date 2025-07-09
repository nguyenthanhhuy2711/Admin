<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$baseUrl = "https://cuddly-exotic-snake.ngrok-free.app";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    $ten_san_pham = $_POST['ten_san_pham'] ?? '';
    $mo_ta = $_POST['mo_ta'] ?? '';
    $gia = $_POST['gia'] ?? '';
    $ma_danh_muc = $_POST['ma_danh_muc'] ?? '';
    $anh_dai_dien = $_FILES['anh_dai_dien'] ?? null;
    $ma_mau = $_POST['ma_mau'] ?? null;
    $files = $_FILES['files'] ?? null;

    // Kiểm tra thiếu dữ liệu
    if (!$ten_san_pham || !$gia || !$ma_danh_muc || !$anh_dai_dien || !$ma_mau || !$files) {
        $_SESSION['toastError'] = 'Thiếu dữ liệu sản phẩm hoặc ảnh biến thể!';
        header('Location: /admin/index.php?page=sanpham');
        exit;
    }

    // Gửi sản phẩm chính
    $data = [
        'ten_san_pham' => $ten_san_pham,
        'mo_ta' => $mo_ta,
        'gia' => $gia,
        'ma_danh_muc' => $ma_danh_muc,
        'file' => new CURLFile($anh_dai_dien['tmp_name'], $anh_dai_dien['type'], $anh_dai_dien['name'])
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "$baseUrl/themSanPham",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $result = json_decode($response, true);
    if ($err || !$result || !isset($result['ma_san_pham'])) {
        $_SESSION['toastError'] = 'Thêm sản phẩm thất bại!';
        header('Location: /admin/index.php?page=sanpham');
        exit;
    }

    $ma_san_pham = $result['ma_san_pham'];

    // Upload ảnh biến thể
    $uploadDir = __DIR__ . '/../../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $successCount = 0;

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === 0) {
            $filename = uniqid() . "_" . basename($files['name'][$i]);
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                $dataSend = [
                    'ma_san_pham' => (int)$ma_san_pham,
                    'ma_mau' => (int)$ma_mau,
                    'files' => new CURLFile($uploadPath)
                ];

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "$baseUrl/themAnhBienThe",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $dataSend,
                ]);

                $response2 = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                if ($httpCode === 200) {
                    $successCount++;
                }
            }
        }
    }

    if ($successCount > 0) {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Thêm sản phẩm thành công với $successCount ảnh biến thể');
            window.location.href = '/admin/index.php?page=sanpham';
        </script>";
    } else {
        echo "<script>
            sessionStorage.setItem('toastSuccess', 'Thêm sản phẩm thành công (⚠️ Không có ảnh biến thể nào được lưu)');
            window.location.href = '/admin/index.php?page=sanpham';
        </script>";
    }
    exit;
}
