<?php
session_start();
include_once('../includes/db.php');

// Initialize error message
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Check for empty fields
    if (empty($username) || empty($email) || empty($phone) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        // Check if email or phone already exists
        $check_stmt = $conn->prepare("SELECT * FROM admins WHERE email = ? OR phone = ?");
        $check_stmt->bind_param("ss", $email, $phone);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error_message = "Email or phone number already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert admin data into the database
            $stmt = $conn->prepare("INSERT INTO admins (username, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Registration successful! You can now log in.";
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Registration failed. Please try again.";
            }

            $stmt->close();
        }
        $check_stmt->close();
    }

    // If there's an error, store it in the session
    $_SESSION['error_message'] = $error_message;
    header("Location: register.php");
    exit();
}

$conn->close();
