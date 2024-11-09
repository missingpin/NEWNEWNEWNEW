<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="maintenance.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Maintenance Check</title>
</head>
<body>

    <h1>Maintenance Check</h1>

    <!-- Database Connection Table -->
    <h2>Database Connection</h2>
    <table class="table">
        <tr>
            <th>Functionality</th>
            <th>Status</th>
            <th>Details</th>
        </tr>
        <?php
        function show_status($functionality, $status, $details) {
            echo "<tr>";
            echo "<td>{$functionality}</td>";
            echo "<td>" . ($status ? "<span class='ok'>OK</span>" : "<span class='error'>Error</span>") . "</td>";
            echo "<td>{$details}</td>";
            echo "</tr>";
        }

        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'database';

        // Database connection check
        try {
            include 'connect.php';
            show_status("Database Connection", true, "Connected successfully");
        } catch (Exception $e) {
            show_status("Database Connection", false, $e->getMessage());
        }
        ?>
    </table>

    <!-- Admin Functionalities Table -->
    <h2>Admin Functionalities</h2>
    <table class="table">
        <tr>
            <th>Functionality</th>
            <th>Status</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
        <?php
        $admin_files = ['dashboard.php', 'admin.php']; // Add your admin files here
        foreach ($admin_files as $file) {
            $exists = file_exists($file);
            echo "<tr>";
            echo "<td>File Check: $file</td>";
            echo "<td>" . ($exists ? "<span class='ok'>OK</span>" : "<span class='error'>Error</span>") . "</td>";
            echo "<td>" . ($exists ? "File exists" : "File does not exist") . "</td>";
            echo "<td><button type='button' class='btn btn-warning' data-toggle='modal' data-target='#errorModal'>View</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- User Functionalities Table -->
    <h2>User Functionalities</h2>
    <table class="table">
        <tr>
            <th>Functionality</th>
            <th>Status</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
        <?php
        $user_files = ['login.php', 'profile.php', 'table.php'];
        foreach ($user_files as $file) {
            $exists = file_exists($file);
            echo "<tr>";
            echo "<td>File Check: $file</td>";
            echo "<td>" . ($exists ? "<span class='ok'>OK</span>" : "<span class='error'>Error</span>") . "</td>";
            echo "<td>" . ($exists ? "File exists" : "File does not exist") . "</td>";
            echo "<td><button type='button' class='btn btn-warning' data-toggle='modal' data-target='#errorModal'>View</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <pre><?php echo htmlspecialchars($errorLog); ?></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
