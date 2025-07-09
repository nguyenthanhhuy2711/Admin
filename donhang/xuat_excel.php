<?php
require __DIR__ . '/../includes/check_login.php';
require __DIR__ . '/../includes/connect.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Gọi API lấy danh sách đơn hàng
$donHangs = callAPI("adminGetAllDonHang") ?? [];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề cột
$sheet->setCellValue('A1', 'STT');
$sheet->setCellValue('B1', 'Mã Đơn');
$sheet->setCellValue('C1', 'Người dùng');
$sheet->setCellValue('D1', 'Người nhận');
$sheet->setCellValue('E1', 'SĐT');
$sheet->setCellValue('F1', 'Địa chỉ');
$sheet->setCellValue('G1', 'Phương thức giao');
$sheet->setCellValue('H1', 'Trạng thái');
$sheet->setCellValue('I1', 'Ngày tạo');

$row = 2;
$stt = 1;
foreach ($donHangs as $dh) {
    $sheet->setCellValue("A$row", $stt++);
    $sheet->setCellValue("B$row", $dh['ma_don_hang'] ?? '');
    $sheet->setCellValue("C$row", $dh['ten_nguoi_dung'] ?? '');
    $sheet->setCellValue("D$row", $dh['ten_nguoi_nhan'] ?? '');
    $sheet->setCellValue("E$row", $dh['so_dien_thoai'] ?? '');
    $sheet->setCellValue("F$row", $dh['dia_chi_giao_hang'] ?? '');
    $sheet->setCellValue("G$row", $dh['ten_phuong_thuc'] ?? '');
    $sheet->setCellValue("H$row", $dh['trang_thai'] ?? '');
    $sheet->setCellValue("I$row", date('d/m/Y H:i:s', strtotime($dh['ngay_tao'] ?? '')));
    $row++;
}

// Gửi file về trình duyệt
$filename = 'danhsach_donhang.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
