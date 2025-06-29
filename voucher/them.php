<?php
session_start();
require_once __DIR__ . '/../includes/connect.php';

// Hàm chuyển datetime-local HTML sang định dạng API cần
function formatDateTimeLocal($datetimeLocal)
{
    if (!$datetimeLocal) return null;
    return str_replace('T', ' ', $datetimeLocal) . ':00';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và chuẩn hóa dữ liệu form
    $ma_voucher = trim($_POST['ma_voucher'] ?? '');
    $mo_ta_hien_thi = trim($_POST['mo_ta_hien_thi'] ?? '');
    $loai = trim($_POST['loai'] ?? '');
    $kieu_giam = trim($_POST['kieu_giam'] ?? '');
    $gia_tri = (float)($_POST['gia_tri'] ?? 0);
    $dieu_kien_ap_dung = (float)($_POST['dieu_kien_ap_dung'] ?? 0);
    $so_luong = (int)($_POST['so_luong'] ?? 1);
    $ngay_bat_dau = formatDateTimeLocal($_POST['ngay_bat_dau'] ?? '');
    $ngay_ket_thuc = formatDateTimeLocal($_POST['ngay_ket_thuc'] ?? '');
    $hien_thi_auto = isset($_POST['hien_thi_auto']) ? (bool)$_POST['hien_thi_auto'] : false;
    $trang_thai = trim($_POST['trang_thai'] ?? 'hoat_dong');
    $nguoi_tao = $_SESSION['user_id'] ?? 1;

    // Kiểm tra ảnh upload bắt buộc
    if (!isset($_FILES['hinh_anh']) || $_FILES['hinh_anh']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Bạn phải chọn ảnh voucher'); history.back();</script>";
        exit;
    }

    // Chuẩn bị dữ liệu gửi API
    $postFields = [
        "ma_voucher" => $ma_voucher,
        "mo_ta_hien_thi" => $mo_ta_hien_thi,
        "loai" => $loai,
        "kieu_giam" => $kieu_giam,
        "gia_tri" => $gia_tri,
        "dieu_kien_ap_dung" => $dieu_kien_ap_dung,
        "so_luong" => $so_luong,
        "ngay_bat_dau" => $ngay_bat_dau,
        "ngay_ket_thuc" => $ngay_ket_thuc,
        "hien_thi_auto" => $hien_thi_auto,
        "trang_thai" => $trang_thai,
        "nguoi_tao" => $nguoi_tao,
        // Gửi file ảnh với CURLFile
        "hinh_anh" => new CURLFile(
            $_FILES['hinh_anh']['tmp_name'],
            $_FILES['hinh_anh']['type'],
            $_FILES['hinh_anh']['name']
        )
    ];

    // Gọi API FastAPI
    $curl = curl_init("https://cuddly-exotic-snake.ngrok-free.app/themVoucher");
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // Xử lý lỗi curl
    if ($err) {
        echo "<script>alert('Gọi API thất bại: " . addslashes($err) . "'); history.back();</script>";
        exit;
    }

    // Phân tích kết quả JSON
    $result = json_decode($response, true);

    if (isset($result['message'])) {
        echo "<script>
        sessionStorage.setItem('toastSuccess', 'Thêm voucher thành công');
        window.location.href = '../index.php?page=voucher';
    </script>";
    } else {
        $error = $result['detail'] ?? 'Lỗi khi thêm voucher';
        echo "<script>
        sessionStorage.setItem('toastError', " . json_encode($error) . ");
        history.back();
    </script>";
    }
}
