<?php
session_start();
include_once('db.php');

// Handle adding a new product
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // File upload settings
    $targetDir = "../assets/images/upload/";
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $imageName;

    // Check if target directory exists, create it if not
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Validate file upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = "No file uploaded or error in file upload.";
        header("Location: ../admin/add_product.php");
        exit();
    }

    $imageError = $_FILES['image']['error'];
    $imageSize = $_FILES['image']['size'];
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Check file size
    if ($imageSize > $maxFileSize) {
        $_SESSION['message'] = "The file size exceeds the allowed limit of 2MB.";
        header("Location: ../admin/add_product.php");
        exit();
    }

    // Validate file type
    if (!in_array($imageFileType, $validExtensions)) {
        $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        header("Location: ../admin/add_product.php");
        exit();
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = "assets/images/upload/" . $imageName;

        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO menu (name, category, price, image, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $category, $price, $imagePath, $description);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Menu item added successfully!";
        } else {
            $_SESSION['message'] = "Database error: Unable to add menu item.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to upload image.";
    }

    header("Location: ../admin/manage_menu.php");
    exit();
}

// Handle updating a product
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $product_id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // File upload settings
    $targetDir = "../assets/images/upload/";
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    $imagePath = '';  // Initialize image path (it will be updated if a new image is uploaded)

    // Check if file is uploaded for updating
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageError = $_FILES['image']['error'];
        $imageSize = $_FILES['image']['size'];
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check file size
        if ($imageSize > $maxFileSize) {
            $_SESSION['message'] = "The file size exceeds the allowed limit of 2MB.";
            header("Location: ../admin/edit_product.php?id=$product_id");
            exit();
        }

        // Validate file type
        if (!in_array($imageFileType, $validExtensions)) {
            $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: ../admin/edit_product.php?id=$product_id");
            exit();
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $_SESSION['message'] = "Failed to upload image.";
            header("Location: ../admin/edit_product.php?id=$product_id");
            exit();
        }

        $imagePath = "../assets/images/upload/" . $imageName;
    }

    // If no new image is uploaded, fetch the old image
    if (!$imagePath) {
        $stmt = $conn->prepare("SELECT image FROM menu WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $oldImage = $result->fetch_assoc()['image'];
        $imagePath = $oldImage;
    }

    // Update product details in the database
    $stmt = $conn->prepare("UPDATE menu SET name = ?, category = ?, price = ?, image = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssdssi", $name, $category, $price, $imagePath, $description, $product_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Menu item updated successfully!";
    } else {
        $_SESSION['message'] = "Database error: Unable to update menu item.";
    }
    $stmt->close();

    header("Location: ../admin/manage_menu.php");
    exit();
}

// Handle deleting a product
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Get the product image path
    $stmt = $conn->prepare("SELECT image FROM menu WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Delete the product image from the server
    if ($product && file_exists("../" . $product['image'])) {
        unlink("../" . $product['image']);
    }

    // Delete product from the database
    $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Menu item deleted successfully!";
    } else {
        $_SESSION['message'] = "Database error: Unable to delete menu item.";
    }
    $stmt->close();

    header("Location: ../admin/manage_menu.php");
    exit();
}
