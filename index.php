<?php
session_start();
include_once('includes/db.php');
include_once('includes/head.php');
?>

<body class="homepage">
    <?php include_once('includes/header.php'); ?>

    <div class="container container-fluid text-center" style="margin-top: 10rem;">
        <h1 class="home-head">Welcome to Our Restaurant!</h1>
        <p class="home-sub-head">Your favorite food awaits you.</p>
        <a href="includes/order.php" class="btn home-btn">Start Ordering</a>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>

</html>