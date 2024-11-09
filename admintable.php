<?php
include 'connect.php';

if (isset($_POST['displaySend'])) {
    $table = '
    <style>
        .table tbody {
            background-color: white;
        }
    </style>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">User ID</th>
                <th scope="col">Email</th>
                <th scope="col">Username</th>
                <th scope="col">Password</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>';

    $sql = "SELECT * FROM form";
    $result = mysqli_query($con, $sql);
    $number = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $user_id = $row['user_id'];
        $email = $row['email'];
        $username = $row['username'];
        $password = $row['password'];
        $status = $row['status'];

        $actionButtons = '';

        if ($status === 'pending') {
            $actionButtons .= '<button class="btn btn-success btn-sm" onclick="approveUser(' . $id . ')">Approve</button>
                               <button class="btn btn-danger btn-sm" onclick="declineUser(' . $id . ')">Decline</button>';
        } else if ($status === 'approved') {
            $actionButtons .= '<button class="btn btn-primary btn-sm" onclick="edituser(' . $id . ')">Edit</button>
                               <button class="btn btn-danger btn-sm" onclick="deleteuser(' . $id . ')">Delete</button>';
        }

        $table .= '<tr>
            <td scope="row">' . $number . '</td>
            <td>' . $user_id . '</td>
            <td>' . $email . '</td>
            <td>' . $username . '</td>
            <td>' . $password. ' </td>
            <td>' . ucfirst($status) . '</td>
            <td style="width: 250px;">' . $actionButtons . '</td>
        </tr>';

        $number++;
    }

    $table .= '</tbody></table>';
    echo $table;
}
?>
