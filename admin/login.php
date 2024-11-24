<?php
session_start();

// Check for and display any messages
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

// Clear messages after displaying
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>

<?php include_once('../includes/head.php'); ?>

<body class="login-page text-white">
    <div class="container-fluid" style="margin-top: 6.1rem;">
        <div class="row">
            <?php include_once('../includes/header.php'); ?>

            <div class="col-md-5 d-flex align-items-center justify-content-center bg-dark login-left">
                <!-- Login Form -->
                <div class="form-section login-form-section" id="login-form">
                    <h2 class="text-white text-center">Admin Login</h2>

                    <!-- Display Success and Error Messages -->
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form action="login_process.php" class="mt-3" method="POST">
                        <div class="mb-3">
                            <label for="user_identifier" class="form-label">Username, Email, or Phone Number</label>
                            <input type="text" class="form-control" id="user_identifier" name="user_identifier" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn home-btn w-100">Login</button>
                    </form>
                    <p class="mt-3 text-center">Don't have an account? <a href="#" onclick="showRegistrationForm()">Register here</a></p>
                </div>

                <!-- Registration Form -->
                <div class="form-section registration-form-section" id="registration-form">
                    <h2 class="text-white">Admin Registration</h2>

                    <!-- Display Error Message -->
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form action="register_process.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn home-btn w-100">Register</button>
                    </form>
                    <p class="mt-3 text-center">Already have an account? <a href="#" onclick="showLoginForm()">Login here</a></p>
                </div>
            </div>

            <!-- Right Side Background Image -->
            <div class="col-md-7 d-none d-md-block login-right" style="background-image: url('../assets/images/login-right.jpg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <?php include_once('../includes/footer.php'); ?>

    <script>
        function showRegistrationForm() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('registration-form').style.display = 'block';
        }

        function showLoginForm() {
            document.getElementById('registration-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }
    </script>
</body>

</html>