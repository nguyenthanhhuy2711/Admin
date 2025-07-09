<?php
require __DIR__ . '/../includes/check_login.php';
require __DIR__ . '/../includes/connect.php';

// Sử dụng thư viện PhpSpreadsheet
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Lấy dữ liệu từ API
$data = callAPI("getAllBienTheSanPham") ?? [];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề các cột
$sheet->setCellValue('A1', 'STT');
$sheet->setCellValue('B1', 'Tên sản phẩm');
$sheet->setCellValue('C1', 'Kích thước');
$sheet->setCellValue('D1', 'Màu sắc');
$sheet->setCellValue('E1', 'Số lượng tồn');

// Ghi dữ liệu
$row = 2;
$stt = 1;
foreach ($data as $item) {
    $sheet->setCellValue("A$row", $stt++);
    $sheet->setCellValue("B$row", $item['ten_san_pham'] ?? '');
    $sheet->setCellValue("C$row", $item['kich_thuoc'] ?? '');
    $sheet->setCellValue("D$row", $item['ten_mau'] ?? '');
    $sheet->setCellValue("E$row", $item['so_luong_ton'] ?? 0);
    $row++;
}

// Gửi file về trình duyệt
$filename = 'danhsach_bienthe_sanpham.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
