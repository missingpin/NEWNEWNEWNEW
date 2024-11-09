<?php
require 'vendor/autoload.php'; // Make sure you have Composer's autoloader
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
include 'connect.php';

$query = "SELECT * FROM product";
$result = mysqli_query($con, $query);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header row
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Image');
$sheet->setCellValue('C1', 'Product Name');
$sheet->setCellValue('D1', 'Type');
$sheet->setCellValue('E1', 'Quantity');
$sheet->setCellValue('F1', 'Expiration');

// Fetch product data
$sql = "SELECT * FROM product";
$result = mysqli_query($con, $sql);
$rowNumber = 2;

while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['id']);
    if (isset($row['image']) && !empty($row['image'])) {
        $imagePath = 'C:\\xampp\\htdocs\\uploads\\' . $row['image']; // Full path to image
        if (file_exists($imagePath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Image');
            $drawing->setDescription('Image');
            $drawing->setPath($imagePath);
            $drawing->setCoordinates('B' . $rowNumber);
            $drawing->setHeight(50); // Set image height
            $drawing->setWorksheet($sheet);
        }
    }
    $sheet->setCellValue('C' . $rowNumber, $row['productname']);
    $sheet->setCellValue('D' . $rowNumber, $row['type']);
    $sheet->setCellValue('E' . $rowNumber, $row['quantity']);
    $sheet->setCellValue('F' . $rowNumber, $row['exp']);

    

    $rowNumber++;
}


$writer = new Xlsx($spreadsheet);
$filename = 'products.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
