<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$baseUrl = "https://cuddly-exotic-snake.ngrok-free.app";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_san_pham = $_POST['ma_san_pham'] ?? null;
    $ma_mau = $_POST['ma_mau'] ?? null;

    if ($ma_san_pham && $ma_mau && isset($_FILES['files'])) {
        $files = $_FILES['files'];
        $uploadDir = __DIR__ . '/../../uploads/';
        $successCount = 0;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Duyệt qua tất cả ảnh được chọn
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === 0) {
                $filename = basename($files['name'][$i]);
                $uploadPath = $uploadDir . $filename;

                if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                    $data = [
                        'ma_san_pham' => (int)$ma_san_pham,
                        'ma_mau' => (int)$ma_mau,
                        'files' => new CURLFile($uploadPath)
                    ];

                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "$baseUrl/themAnhBienThe",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $data,
                    ]);

                    $response = curl_exec($curl);
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
            sessionStorage.setItem('toastSuccess', 'Đã thêm $successCount ảnh thành công');
            window.location.href = '/admin/index.php?page=anhbienthe';
        </script>";
        } else {
            echo "<script>
            sessionStorage.setItem('toastError', 'Không thêm được ảnh nào');
            window.location.href = '/admin/index.php?page=anhbienthe';
        </script>";
        }
        exit;
    }
}
