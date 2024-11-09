<?php
session_start();
include 'connect.php';
include 'sidebar.php';

// Check if the user is logged in (for example, through a session variable)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name, profile_image FROM userinfo WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($first_name, $last_name, $profile_image);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="#.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>User Profile</h1>
        </div>
        <div class="profile-details">
            <img src="uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-img">
            
            <div class="user-info">
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></p>
            </div>
        </div>
        
        <!-- Edit Profile Link (optional) -->
        <a href="editprofile.php" class="edit-profile-btn">Edit Profile</a>
    </div>
</body>
</html>

