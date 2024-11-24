<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include_once('../includes/db.php');
include_once('../includes/admin_head.php');
include_once('../includes/admin_navbar.php');

// Fetch categories from the database
$categories = [];
$stmt = $conn->prepare("SELECT id, name FROM categories");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
$stmt->close();
?>

<body>
    <div class="container mt-5">
        <h2>Manage Categories</h2>

        <!-- Display Notifications -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Add Category Form -->
        <form method="POST" action="../includes/category_functions.php">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>

        <!-- Categories Table -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td>
                            <a href="../includes/category_functions.php?action=delete&id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete category?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>