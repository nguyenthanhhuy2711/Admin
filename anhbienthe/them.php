<?php
include __DIR__ . '/../includes/check_login.php';
include __DIR__ . '/../includes/connect.php';

$baseUrl = "https://cuddly-exotic-snake.ngrok-free.app";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_san_pham = $_POST['ma_san_pham'] ?? null;
    $ma_mau = $_POST['ma_mau'] ?? null;

    if ($ma_san_pham && $ma_mau && isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $file = $_FILES['file'];
        $filename = basename($file['name']);
        $uploadDir = __DIR__ . '/../../uploads/';
        $uploadPath = $uploadDir . $filename;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
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
                echo "<script>
                    sessionStorage.setItem('toastSuccess', 'Thêm ảnh biến thể thành công');
                    window.location.href = '/admin/index.php?page=anhbienthe';
                </script>";
                exit;
            } else {
                echo "<script>
                    sessionStorage.setItem('toastError', 'Lỗi khi thêm ảnh biến thể');
                    window.location.href = '/admin/index.php?page=anhbienthe';
                </script>";
                exit;
            }
        } else {
            echo "<script>
                sessionStorage.setItem('toastError', 'Tải lên ảnh thất bại');
                window.location.href = '/admin/index.php?page=anhbienthe';
            </script>";
            exit;
        }
    } else {
        echo "<script>
            sessionStorage.setItem('toastError', 'Thiếu dữ liệu hoặc lỗi ảnh');
            window.location.href = '/admin/index.php?page=anhbienthe';
        </script>";
        exit;
    }
}
