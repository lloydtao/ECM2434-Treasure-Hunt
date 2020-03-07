<html>
<head>
    <meta name="author" content="Jakub Kwak">
    <title>Reset Password - CampusTreks</title>
    <?php include('templates/head.php'); ?>
    <?php
    // Redirect to home.php if already logged in
    include "checklogin.php";
    if (CheckLogin()) {
        header("location:index.php");
    }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
    </script>
</head>
<body>
<!-- Header -->
<?php include('templates/header.php'); ?>
<!-- Content -->
<main class="page login-page">
    <section class="portfolio-block contact">
        <div class="container">
            <div class="heading">
                <h2>Reset Password</h2>
            </div>
            <form id="request-reset-form" method="post" action="send_recovery_email.php">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input class="form-control item" type="text" name="email" id="email" required>
                </div>
                <button class="btn btn-primary btn-block btn-lg" type="submit">Send Recovery Email</button>
            </form>
        </div>
    </section>
</main>
<!-- Footer -->
<?php include('templates/footer.php'); ?>
</body>
</html>

