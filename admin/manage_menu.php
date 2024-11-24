<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include_once('../includes/db.php');
include_once('../includes/admin_head.php');
include_once('../includes/admin_navbar.php');

// Fetch all menu items
$sql = "SELECT menu.id, menu.name, categories.name AS category, menu.price, menu.image, menu.description 
        FROM menu 
        JOIN categories ON menu.category = categories.id";
$result = $conn->query($sql);
$menu_items = $result->fetch_all(MYSQLI_ASSOC);

?>

<body>
    <div class="container mt-5">
        <h2>Manage Menu</h2>

        <!-- Notifications -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <!-- Add Product Button -->
        <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>

        <!-- Menu Items Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menu_items as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['category']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td>
                            <img src="../<?php echo $item['image']; ?>" alt="Menu Image" style="width: 50px; height: 50px;">
                        </td>
                        <td><?php echo $item['description']; ?></td>
                        <td>
                            <a href="edit_menu.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../includes/menu_functions.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>