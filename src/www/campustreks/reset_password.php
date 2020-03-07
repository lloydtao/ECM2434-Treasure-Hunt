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
        $(document).ready(function () {
            $("#reset-form").submit(function (e) {
                e.preventDefault();
                $("#password-confirm-error").css("display", "none");
                $("#reset-error").css("display", "none");
                $("#session-error").css("display", "none");
                $("#form-error").css("display", "none");
                $.ajax({
                    type: "POST",
                    url: "reset_password_handler.php",
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data === "success") {
                            window.location = "login.php";
                        } else if (data === "password-error") {
                            $("#password-confirm-error").css("display", "block");
                        } else if (data === "reset-error") {
                            $("#reset-error").css("display", "block");
                        } else if (data === "session-error") {
                            $("#session-error").css("display", "block");
                        } else if (data === "form-error") {
                            $("#form-error").css("display", "block");
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
                <h2>Reset Password</h2>
            </div>
            <form id="reset-form" method="post">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input class="form-control item" type="text" name="email" id="email">
                </div>
                <div class="form-group">
                    <label for="recovery-code">Recovery Code</label>
                    <input class="form-control item" type="text" name="recoveryCode" id="recovery-code">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control item" type="password" name="password" id="password" minlength="8">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input class="form-control item" type="password" name="confirmPassword" id="confirm_password">
                    <p id="password-confirm-error" class="form-group" style="display: none">Passwords do not match</p>
                </div>
                <button class="btn btn-primary btn-block btn-lg" type="submit">Change Password</button>
                <p id="reset-error" class="form-group" style="display: none">Email or recovery code incorrect</p>
                <p id="session-error" class="form-group" style="display: none">Session expired, please request another recovery code</p>
                <p id="form-error" class="form-group" style="display: none">Please fill in all fields</p>
            </form>
        </div>
    </section>
</main>
<!-- Footer -->
<?php include('templates/footer.php'); ?>
</body>
</html>

