<?php
require_once __DIR__ . '/../vendor/autoload.php';

include __DIR__ . '/../includes/connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$products = callAPI("getallSanPham");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tiêu đề cột
$sheet->setCellValue('A1', 'STT');
$sheet->setCellValue('B1', 'Tên sản phẩm');
$sheet->setCellValue('C1', 'Giá (VND)');
$sheet->setCellValue('D1', 'Mô tả');

// Đổ dữ liệu
$row = 2;
$stt = 1;
if (is_array($products)) {
    foreach ($products as $sp) {
        $sheet->setCellValue('A' . $row, $stt++);
        $sheet->setCellValue('B' . $row, $sp['ten_san_pham'] ?? '');
        $sheet->setCellValue('C' . $row, $sp['gia'] ?? 0);
        $sheet->setCellValue('D' . $row, $sp['mo_ta'] ?? '');
        $row++;
    }
}
$sheet->getColumnDimension('D')->setAutoSize(true);

// Xuất file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="danh_sach_san_pham.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
