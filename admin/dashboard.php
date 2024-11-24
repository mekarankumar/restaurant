<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit();
}
include_once('../includes/db.php');
include_once('../includes/admin_head.php');
include_once('../includes/admin_navbar.php');

// Fetch statistics
$total_items = $conn->query("SELECT COUNT(*) as total FROM menu")->fetch_assoc()['total'];
$total_categories = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];
?>

<body>
    <div class="container mt-5 dashboard-home">
        <h1>Admin Dashboard</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Total Menu Items</h5>
                        <p class="card-text"><?php echo $total_items; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <p class="card-text"><?php echo $total_categories; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>