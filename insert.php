<?php
include 'connect.php';

if (isset($_POST['productSend']) && isset($_POST['typeSend']) && isset($_POST['quantitySend']) && isset($_POST['expirationSend']) && isset($_POST['saleSend']) && isset($_POST['purchaseSend']) && isset($_POST['descriptionSend']) && isset($_FILES['imageSend'])) {

    // Get product details from POST
    $productSend = $_POST['productSend'];
    $quantitySend = $_POST['quantitySend'];
    $expirationSend = $_POST['expirationSend'];
    $typeSend = $_POST['typeSend'];
    $saleSend = $_POST['saleSend'];
    $purchaseSend = $_POST['purchaseSend'];
    $descriptionSend = $_POST['descriptionSend'];
    
    // Handle image upload
    $image = $_FILES['imageSend'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    
    $uploadDir = 'uploads/';
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $newImageName = uniqid("IMG_", true) . '.' . $imageExtension;
    $uploadFile = $uploadDir . $newImageName;

    // Check if the directory exists, if not, create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if file is a valid image type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageExtension, $allowedTypes)) {
        echo "Error: Only image files (JPG, JPEG, PNG, GIF) are allowed.";
        exit();
    }

    // Move the uploaded file
    if (move_uploaded_file($imageTmpName, $uploadFile)) {
        // Insert the product into the database
        $stmt = $con->prepare("INSERT INTO product (productname, quantity, exp, sale, purchase, description, image, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisiisss", $productSend, $quantitySend, $expirationSend, $saleSend, $purchaseSend, $descriptionSend, $newImageName, $typeSend);
        
        if ($stmt->execute()) {
            // Product inserted successfully, now log the activity
            $activityDescription = "Added a new product: $productSend";

            // Insert into activity_log table
            $logStmt = $con->prepare("INSERT INTO activity_log (activity_description) VALUES (?)");
            $logStmt->bind_param("s", $activityDescription);

            if ($logStmt->execute()) {
                // Activity logged successfully
                echo "Product added successfully and activity logged!";
            } else {
                echo "Error: Could not log activity.";
            }

            $logStmt->close();
            $stmt->close();
        } else {
            echo "Error: Could not insert product.";
        }
    } else {
        echo "Error: File upload failed.";
    }

    // Optional: Check stock and send alert if needed (low stock alert)
    $lowStockThreshold = 5;
    if ($quantitySend < $lowStockThreshold) {
        $userEmail = 'user_email@example.com'; // Replace with dynamic user email if needed
        $productName = $productSend;
        $currentQuantity = $quantitySend;

        // Send low stock alert (Assuming you have a function for that)
        if (sendLowStockAlert($userEmail, $productName, $currentQuantity)) {
            echo "Low stock alert sent!";
        } else {
            echo "Failed to send low stock alert.";
        }
    }

} else {
    echo "Error: Missing product details or image.";
}
?>
