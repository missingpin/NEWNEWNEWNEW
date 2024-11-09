<?php
session_start();
include("connect.php");

function generateUniqueUserId($con) {
    do {
        $randomUserId = rand(100000, 999999); 

        $checkQuery = "SELECT COUNT(*) FROM form WHERE user_id = ?";
        $stmt = $con->prepare($checkQuery);

        if ($stmt === false) {
            die('Error preparing statement: ' . $con->error);
        }

        $stmt->bind_param("i", $randomUserId);
        $stmt->execute();

        if ($stmt->error) {
            // Handle query execution error
            die('Error executing query: ' . $stmt->error);
        }

        // Get the result using get_result (for a single value like COUNT(*))
        $result = $stmt->get_result(); 
        $row = $result->fetch_row();  // Fetch the result (it's a single row with a single column)

        // Check if the user_id already exists (if the count > 0, regenerate ID)
        $count = $row[0]; // $row[0] contains the count value

    } while ($count > 0);  // If the ID already exists, regenerate

    return $randomUserId;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (!preg_match($passwordPattern, $password)) {
            echo "<script>alert('Password must be at least 8 characters long, include at least one uppercase letter, one number, and one special character.');</script>";
        } else {
            $stmt = $con->prepare("SELECT * FROM form WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Email or username already taken.');</script>";
            } else {
                // Generate a unique user_id
                $user_id = generateUniqueUserId($con);

                $level = 1;  // Default level for new users

                // Insert new user with the generated user_id
                $query = "INSERT INTO form (user_id, email, username, password, level, status) VALUES (?, ?, ?, ?, ?, 'pending')";
                $stmt = $con->prepare($query);
                $stmt->bind_param("isssi", $user_id, $email, $username, $password, $level);

                if ($stmt->execute()) {
                    echo "<script>alert('Successfully Registered. Awaiting admin approval.');</script>";
                    echo "<script>window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('Error: Could not register');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Please enter valid information.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="wrapper">
        <form action="" method="POST">
            <h1>Sign Up</h1>

            <div class="input-group">
                <div class="input-field" id="emailField">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                <div class="input-field" id="nameField">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" placeholder="Username" name="username" required>
                </div>

                <div class="input-field" id="pwField">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" required>
                </div>

                <button type="submit" class="btn">Sign Up</button>

                <div class="login-link">
                    <a href="login.php">Back to Login</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const password = document.querySelector('[name="password"]').value;
        const pwField = document.getElementById('pwField');
        
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!passwordPattern.test(password)) {
            event.preventDefault();
            alert('Password must be at least 8 characters long, include at least one uppercase letter, one number, and one special character.');
            pwField.querySelector('input').focus();
        }
    });
</script>
