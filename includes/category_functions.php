<?php
session_start();
include_once('db.php');

// Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Category added successfully!";
    } else {
        $_SESSION['message'] = "Error adding category.";
    }
    $stmt->close();

    header("Location: ../admin/manage_categories.php");
    exit();
}

// Delete Category
if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Category deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting category.";
    }
    $stmt->close();

    header("Location: ../admin/manage_categories.php");
    exit();
}
