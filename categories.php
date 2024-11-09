<?php
session_start();
include 'connect.php';
include 'sidebar.php';

// Initialize error message
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the category from he form
    $ptype = $_POST['ptype'];
    $etype = $_POST['etype'];

    $stmt = $con->prepare("SELECT COUNT(*) FROM category WHERE ptype = ?");
    $stmt->bind_param("s", $ptype);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $error_message = "Category '$ptype' already exists!";
    } else {
        $stmt = $con->prepare("INSERT INTO category (ptype, etype) VALUES (?, ?)");
        $stmt->bind_param("ss", $ptype, $etype);
        $stmt->execute();
        $stmt->close();

        // Log the activity (no user_id needed)
        $activity_desc = "Added a new category: $ptype";
        $stmt = $con->prepare("INSERT INTO activity_log (activity_description) VALUES (?)");
        $stmt->bind_param("s", $activity_desc);
        $stmt->execute();
        $stmt->close();
    }
}

$result = $con->query("SELECT * FROM category");
$categories = '';
$number = 1; // Initialize the counter to 1
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $categories .= "<tr>
        <td style='text-align: center;'>$number</td>
        <td style='text-align: center;' class='category-name'>{$row['ptype']}</td>
        <td style='text-align: center;' class='expiry-type'>{$row['etype']}</td>
        <td>
            <button class='btn btn-primary btn-sm' onclick='editline($id)'>Edit</button>
            <button class='btn btn-danger btn-sm' onclick='deleteline($id)'>Delete</button>
        </td>
    </tr>";
    $number++;
}

$table = '<table class="table table-striped table-bordered" id="categoryTable">
    <thead class="thead-dark">
        <tr>
            <th scope="col" style="text-align: center;">ID</th>
            <th scope="col" style="text-align: center;">Category</th>
            <th scope="col" style="text-align: center;">Expiry Type</th>
            <th scope="col" style="text-align: center;">Actions</th> 
        </tr>
    </thead>
    <tbody>
        ' . $categories . '
    </tbody>
</table>';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="categories.css">
    <title>Categories</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="header">Categories</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
        Add Category
    </button>
    <input type="text" id="searchInput" class="search-input" placeholder="Search..." onkeyup="filterCategories()">

    <!-- Display error message if exists -->
    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <?php echo $table; ?>

    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="categoryForm" method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="ptype">Category Name</label>
                            <input type="text" class="form-control" id="ptype" name="ptype" required>
                        </div><div class="form-group">
                            <label for="etype">Expiration Type</label>
                            <select class="form-control" id="etype" name="etype" required>
                                <option value="" disabled selected>-</option>
                                <option value="Expiry">Expiry</option>
                                <option value="Non-expiry">Non-Expiry</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('etype').addEventListener('focus', function() {
        var placeholderOption = this.querySelector('option[value=""]');
        if (placeholderOption) {
            placeholderOption.style.display = 'none';
        }
    });
    const ptypeInput = document.getElementById('ptype');

ptypeInput.addEventListener('keydown', function(e) {
if (
  e.key === 'Backspace' || 
  e.key === 'Delete' || 
  (e.keyCode >= 37 && e.keyCode <= 40) ||
  e.key === 'Tab' ||
  e.key === ' '
) {
  return;
}

if (/[^A-Za-z0-9]/.test(e.key)) {
  e.preventDefault();
}
});

const searchInput = document.getElementById('searchInput');

searchInput.addEventListener('keydown', function(e) {
if (
  e.key === 'Backspace' || 
  e.key === 'Delete' || 
  (e.keyCode >= 37 && e.keyCode <= 40) ||
  e.key === 'Tab' ||
  e.key === ' '
) {
  return;
}

if (/[^A-Za-z0-9]/.test(e.key)) {
  e.preventDefault();
}
});


    $('#addCategoryModal').on('hidden.bs.modal', function () {
        $('#categoryForm')[0].reset();
    });

    function filterCategories() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const categoryTable = document.getElementById('categoryTable');
        const rows = categoryTable.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const categoryName = rows[i].getElementsByClassName('category-name')[0].textContent.toLowerCase();
            if (categoryName.includes(searchInput)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>
</body>
</html>
