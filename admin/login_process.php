<?php
session_start();
include_once('../includes/db.php');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_identifier = $_POST['user_identifier'];
    $password = $_POST['password'];

    // Fetch user details based on username, email, or phone
    $stmt = $conn->prepare("SELECT * FROM admins WHERE (username = ? OR email = ? OR phone = ?) LIMIT 1");
    $stmt->bind_param("sss", $user_identifier, $user_identifier, $user_identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check password - assuming password is hashed
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "Invalid credentials!";
    }
    $stmt->close();
}

// Store the error message in the session and redirect back to the login page
$_SESSION['error_message'] = $error_message;
header("Location: login.php");
exit();
