<!-- <?php
        session_start();
        ?>

<?php include_once('../includes/head.php'); ?>
<?php include_once('../includes/header.php'); ?>

<body>
    <div class="container container-fluid d-flex justify-content-center align-items-center mt-4">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Admin Registration</h2>
            <form action="register_process.php" method="POST">
                <div class="mb-2">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-2">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn home-btn w-100">Register</button>
            </form>
        </div>
    </div>

    <?php include_once('../includes/footer.php'); ?>
</body>

</html> -->