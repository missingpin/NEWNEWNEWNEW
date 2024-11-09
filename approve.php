<?php
include("connect.php");

if(isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $query = "UPDATE form SET status = 'approved' WHERE id = '$userId'";

    if(mysqli_query($con, $query)) {
        echo "User approved successfully.";
    } else {
        echo "Error approving user.";
    }
}
?>
