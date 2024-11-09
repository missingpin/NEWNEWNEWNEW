<?php

include 'connect.php'; // Include your database connection

if (isset($_POST['productname'])) {
    $productname = $_POST['productname'];
    
    // Query to check if the product name already exists
    $sql = "SELECT COUNT(*) as count FROM products WHERE productname = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productname);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false]);
}
?>
