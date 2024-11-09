<?php
include 'connect.php';

if ($con) {
    $query = "SELECT productname, quantity, exp FROM product 
              WHERE quantity <= 50 OR (DATEDIFF(exp, CURDATE()) <= 7 AND DATEDIFF(exp, CURDATE()) >= 0) 
              ORDER BY last_updated DESC";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $alerts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if (isset($row['productname']) && isset($row['quantity'])) {
        $daysLeft = (strtotime($row['exp']) - time()) / (60 * 60 * 24);
        $daysLeft = round($daysLeft);
        
        if ($row['quantity'] <= 50) {
            $alerts[] = [
                'message' => "Low-stock for: <strong>" . htmlspecialchars($row['productname']) . "</strong> - " . $row['quantity'] . " left!",
                'type' => 'low-stock'
            ];            
        }
        if ($daysLeft <= 7 && $daysLeft >= 0) {
            $alerts[] = [
                'message' => "<strong>" . htmlspecialchars($row['productname']) . "</strong> is about to expire in " . $daysLeft . " days!",
                'type' => 'expiration'
            ];
        }
        
    }
}

    header('Content-Type: application/json');
    echo json_encode($alerts);
} else {
    die("Connection is not established.");
}

mysqli_close($con);
?>
