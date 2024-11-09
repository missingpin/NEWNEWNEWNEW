<?php
session_start();
include 'sidebar.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="admindashboard.css">
    <link rel="stylesheet" href="adminheader.css">
    <link rel="stylesheet" href="sidebar.php">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>User Management</title>
</head>

<body>
    <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Log Out</button>
    </form>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#usermodal">Add User</button>

    <!-- User Approval Modal -->
    <div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="userform">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter Username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning" onclick="adduser()">Insert</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edituserform">
                    <div class="modal-body">
                        <input type="hidden" id="editUserId">
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" class="form-control" id="editUsername" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Password</label>
                            <input type="text" class="form-control" id="editPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container my-3">
        <h1 class="text-center">User List</h1>
        <div id="displayuser"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            displayData();
        });

        function displayData() {
            $.ajax({
                url: "admintable.php",
                type: 'post',
                data: { displaySend: true },
                success: function (data) {
                    $('#displayuser').html(data);
                }
            });
        }

        function adduser() {
            if (!document.getElementById('userform').checkValidity()) {
                alert('Please fill out all required fields.');
                return;
            }
            var formData = new FormData();
            formData.append('usernameSend', $('#username').val());
            formData.append('passwordSend', $('#password').val());
            formData.append('emailSend', $('#email').val());
            $.ajax({
                url: "adduser.php",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    displayData(); // Refresh the user list
                }
            });
            $('#usermodal').modal('hide');
        }

        function edituser(userId) {
            $.post("getuser.php", { userId: userId }, function (data) {
                const user = JSON.parse(data);
                $('#editUserId').val(user.id);
                $('#editUsername').val(user.username);
                $('#editPassword').val(user.password);
                $('#editEmail').val(user.email);
                $('#editusermodal').modal('show');
            });
        }

        function updateUser() {
            var userId = $('#editUserId').val();
            var username = $('#editUsername').val();
            var password = $('#editPassword').val();
            var email = $('#editEmail').val();

            $.post("updateuser.php", {
                userId: userId,
                username: username,
                password: password,
                email: email
            }, function () {
                displayData(); // Refresh the user list
                $('#editusermodal').modal('hide');
            });
        }

        function approveUser(userId) {
            $.post("approve.php", { userId: userId }, function () {
                displayData(); // Refresh the user list
            });
        }

        function declineUser(userId) {
            $.post("decline.php", { userId: userId }, function () {
                displayData(); // Refresh the user list
            });
        }

        function deleteuser(userId) {
            $.post("delete.php", { deleteform: userId }, function () {
                displayData(); // Refresh the user list
            });
        }
    </script>
</body>

</html>
