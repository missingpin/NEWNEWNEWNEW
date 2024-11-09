<?php
$level = isset($_SESSION['level']) ? $_SESSION['level'] : 1;?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="sidebar.css">
    <script src="header.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="menu-toggle">
    <div class="topbar">
        <i class='bx bx-bell' style="font-size: 24px; color: white; margin-left: 20px; cursor: pointer;" onclick="toggleAlerts(); fetchLowStockProducts();"></i>
    </div>
</div>

<div id="alert-container" class="alert-container">
    <div id="low-stock-alerts" class="low-stock-alerts"></div>
    <a href="alerts.php" class="go-to-alerts">Go to Alerts</a>
</div>

<div class="sidebar">
    <div class="logo-details">
        <i class='bx bx-package icon'></i>
        <div class="logo_name">PROJECT</div>
        <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list">
        <li>
            <a href="dashboard.php">
                <i class='bx bx-pie-chart-alt-2'></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="categories.php">
                <i class='bx bx-purchase-tag'></i>
                <span class="links_name">Categories</span>
            </a>
            <span class="tooltip">Categories</span>
        </li>
        <li>
            <a href="table.php">
                <i class='bx bx-box'></i>
                <span class="links_name">Products</span>
            </a>
            <span class="tooltip">Products</span>
        </li>
        <li>
            <a href="dashboard.php">
                <i class='bx bx-grid-alt'></i>
                <span class="links_name">Stock</span>
            </a>
            <span class="tooltip">Stock</span>
        </li>
        <li>
        <?php if ($level == 1): ?>
            <a href="alerts.php">
                <i class='bx bx-notification'></i>
                <span class="links_name">Alerts</span>
            </a>
            <span class="tooltip">Alerts</span>
        </li>
        <?php endif; ?>
        <li>
            <a href="dashboard.php">
                <i class='bx bx-scan'></i>
                <span class="links_name">Scan</span>
            </a>
            <span class="tooltip">Scan</span>
        </li>
        
        <?php if ($level == 2): ?>
        <li>
            <a href="admindashboard.php">
                <i class='bx bx-user'></i>
                <span class="links_name">Users</span>
            </a>
            <span class="tooltip">Users</span>
        </li>
        <?php endif; ?>

        <?php if ($level == 2): ?>
        <li>
            <a href="dashboard.php">
                <i class='bx bx-stats'></i>
                <span class="links_name">Reports</span>
            </a>
            <span class="tooltip">Reports</span>
        </li>
        <?php if ($level == 2): ?>
        <li>
            <a href="dashboard.php">
                <i class='bx bx-wrench'></i>
                <span class="links_name">Maintenance</span>
            </a>
            <span class="tooltip">Maintenance</span>
        </li>
        <?php endif; ?>
        <?php endif; ?>
        <li class="profile">
    <a href="userprofile.php">
        <div class="profile-details">
            <img src="<?php echo isset($user['profile_picture']) && !empty($user['profile_picture']) ? $user['profile_picture'] : 'defaultprofile.jpg'; ?>" alt="profileImg" class="profile-img">
            <div class="name_job">
                <div class="name">User</div>
            </div>
        </div>
    </a>
    <a href="logout.php" class="logout-icon">
        <i class='bx bx-log-out' id="log_out"></i>
    </a>
</li>


    </ul>
</div>

<div class="overlay" id="overlay"></div>

<script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let overlay = document.getElementById("overlay");

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        menuBtnChange();

        if (sidebar.classList.contains("open")) {
            overlay.classList.add('show');
        } else {
            overlay.classList.remove('show');
        }
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove("open");
        overlay.classList.remove('show');
        menuBtnChange();
    });

    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    }

    // Alert functionality
    document.addEventListener("DOMContentLoaded", function() {
        fetchLowStockProducts();
        setInterval(fetchLowStockProducts, 10000);
        document.addEventListener('click', function(event) {
            const alertContainer = document.getElementById('alert-container');
            if (!alertContainer.contains(event.target) && !event.target.classList.contains('bx-bell')) {
                alertContainer.style.display = 'none';
            }
        });
    });

    function toggleAlerts() {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.style.display = alertContainer.style.display === 'none' ? 'block' : 'none';
    }

    function fetchLowStockProducts() {
        console.log("Fetching low stock products...");
        fetch('alertcheck.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("Fetched alerts:", data);
                displayLowStockAlerts(data);
            })
            .catch(error => console.error('Error fetching low stock products:', error));
    }

    function displayLowStockAlerts(products) {
        const alertContainer = document.getElementById('low-stock-alerts');
        alertContainer.innerHTML = '';

        if (products.length === 0) {
            alertContainer.innerHTML = '<p>No low stock alerts.</p>';
        } else {
            const limitedProducts = products.slice(0, 5);
            limitedProducts.forEach(product => {
                const alertItem = document.createElement('div');
                alertItem.className = 'alert alert-warning';
                alertItem.innerHTML = product.message; // Ensure product.message is defined
                alertContainer.appendChild(alertItem);
            });
        }
    }
</script>
</body>
</html>
