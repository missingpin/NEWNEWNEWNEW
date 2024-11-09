<?php
require 'vendor/autoload.php';
include 'connect.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['file'])) {
    // Check for upload errors
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $_FILES['file']['error']);
    }

    $file = $_FILES['file']['tmp_name'];

    // Check if file is not empty
    if (empty($file)) {
        die("No file was uploaded.");
    }

    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    foreach ($sheetData as $row) {
        if ($row['A'] != 'ID') { // Skip header row
            $image = mysqli_real_escape_string($con, $row['B']); 
            $productname = mysqli_real_escape_string($con, $row['C']);
            $type = mysqli_real_escape_string($con, $row['D']);
            $quantity = mysqli_real_escape_string($con, $row['E']);
            $exp = mysqli_real_escape_string($con, $row['F']);
            
            // Insert into database
            $sql = "INSERT INTO product (productname, type, quantity, exp, image) VALUES ('$productname', '$type', $quantity, '$exp', '$image')";
            if (!mysqli_query($con, $sql)) {
                echo "Error: " . mysqli_error($con);
            } else {
                echo "Inserted: $productname, $type, $quantity, $exp, $image<br>";
            }
        }
    }

    echo "Data imported successfully!";
}
?>
