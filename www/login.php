<html>
<head>
    <meta name="author" content="Jakub Kwak">
    <title>Login - CampusTreks</title>
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
        $(document).ready(function () {
            $("#login-form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "loginhandler.php",
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data == "login-success") {
                            window.location = "index.php";
                        } else if (data == "login-fail") {
                            $("#login-error").css("display", "block");
                        }
                    }
                });
            });
        });
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
                <h2>Log In</h2>
            </div>
            <form id="login-form" method="post">
                <div class="form-group">
                    <label for="email">Username or Email</label>
                    <input class="form-control item" type="text" name="email" id="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control item" type="password" name="password" id="password">
                </div>
                <p id="login-error" class="form-group" style="display: none">Username or password incorrect</p>
                <button class="btn btn-primary btn-block btn-lg" type="submit">Log In</button>
                <br>
                <a class="btn btn-primary btn-block btn-lg" id="register-button" href="register.php">Register</a>
            </form>
        </div>
    </section>
</main>
<!-- Footer -->
<?php include('templates/footer.php'); ?>
</body>
</html>
