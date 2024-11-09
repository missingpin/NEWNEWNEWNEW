<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = mysqli_real_escape_string($con, $_POST['ptype']);

    $sql = "INSERT INTO category (type) VALUES ('$type')";
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
    }
} else {
    // If the request method is not POST, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
