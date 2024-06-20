<?php
require 'vendor/autoload.php'; // Ensure this path points to the Composer autoload file
require('connect.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

ob_start(); // Start output buffering

// Set styles for header row
$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'EEEEEE'],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
foreach (range('A', 'H') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Set header
$sheet->setCellValue('A1', 'รหัสสินค้า');
$sheet->setCellValue('B1', 'ชื่อสินค้า');
$sheet->setCellValue('C1', 'รายละเอียดสินค้า');
$sheet->setCellValue('D1', 'ราคาสินค้า');
$sheet->setCellValue('E1', 'ประเภทสินค้า');
$sheet->setCellValue('F1', 'จำนวนคงเหลือ');
$sheet->setCellValue('G1', 'รหัสคลังจัดเก็บ');
$sheet->setCellValue('H1', 'รูปสินค้า');

// Fetch data from database
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

$rowNumber = 2; // Starting from the second row since the first row is the header
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row["ProductID"]);
    $sheet->setCellValue('B' . $rowNumber, $row["ProductName"]);
    $sheet->setCellValue('C' . $rowNumber, $row["ProductDescription"]);
    $sheet->setCellValue('D' . $rowNumber, $row["ProductPrice"]); // เปลี่ยนจาก ProductDescription เป็น ProductPrice
    $sheet->setCellValue('E' . $rowNumber, $row["ProductCategory"]);
    $sheet->setCellValue('F' . $rowNumber, $row["ReorderQuantity"]);
    $sheet->setCellValue('G' . $rowNumber, $row["invenID"]);
    
    // Add image to the worksheet
    if (!empty($row["ProductImage"])) {
        $drawing = new Drawing();
        $drawing->setPath('assets/img/' . $row["ProductImage"]); // Update the path to your images directory
        $drawing->setCoordinates('H' . $rowNumber);
        $drawing->setOffsetX(10); // Set offset X to avoid overlapping
        $drawing->setOffsetY(10); // Set offset Y to avoid overlapping
        $drawing->setWidth(50); // Set the width of the image
        $drawing->setHeight(50); // Set the height of the image
        $drawing->setWorksheet($sheet);
    } else {
        $sheet->setCellValue('H' . $rowNumber, 'No Image');
    }

     // Set row height to fit the image
     $sheet->getRowDimension($rowNumber)->setRowHeight(50);
    
    $rowNumber++;
}

// Set styles for data rows
$dataStyle = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'vertical' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Apply styles to data rows
$sheet->getStyle('A2:H' . ($rowNumber - 1))->applyFromArray($dataStyle);

mysqli_close($conn);

try {
    // Redirect output to a client's web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="รายการสินค้า.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1'); // If you're serving to IE over SSL.

    // Save the spreadsheet
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    ob_end_flush(); // End output buffering and flush all buffers
    exit;
} catch (Exception $e) {
    ob_end_clean(); // End output buffering and clean all buffers
    echo 'Error creating spreadsheet: ', $e->getMessage();
    exit;
}
?>
