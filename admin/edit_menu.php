<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include_once('../includes/db.php');
include_once('../includes/admin_head.php');
include_once('../includes/admin_navbar.php');

// Check if the product ID is passed via GET
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product data from the database
    $stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = "Product not found!";
        header("Location: manage_menu.php");
        exit();
    }
} else {
    $_SESSION['message'] = "No product ID provided!";
    header("Location: manage_menu.php");
    exit();
}

// Fetch all categories for the category dropdown
$categoryResult = $conn->query("SELECT * FROM categories");

?>

<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>

        <!-- Notifications -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <!-- Edit Product Form -->
        <form action="../includes/menu_functions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="" disabled>Select Category</option>
                    <?php while ($category = $categoryResult->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category'] ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"><?php echo $product['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <small>Current image: <img src="../<?php echo $product['image']; ?>" alt="Menu Image" style="width: 50px; height: 50px;"></small>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary" name="action" value="update">Update Product</button>
            </div>
        </form>
    </div>
</body>

</html>