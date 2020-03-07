<html>
<head>
    <meta name="author" content="Joseph Lintern">
    <meta name="contributor" content="Jakub Kwak">
    <title>Register - CampusTreks</title>
    <?php include('templates/head.php'); ?>
    <?php
    // Redirect to home.php if already logged in
    include "checklogin.php";
    if (CheckLogin()) {
        header("location:create.php");
    }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/nbp/nbp_es6.js"></script>
    <script>
        $(document).ready(function () {
            //initalise NBP
            NBP.init("mostcommon_100000", "collections/", true);
            $("#register-form").submit(function (e) {
                e.preventDefault();
                $("#username-error").css("display", "none");
                $("#email-error").css("display", "none");
                $("#password-error").css("display", "none");
                $("#password-match-error").css("display", "none");
                $("#form-error").css("display", "none");
                //check password against top 1000000 common passwords
                if (NBP.isCommonPassword($('input[name=password]').val())) {
                    $("#password-error").css("display", "block");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "registerhandler.php",
                        data: $(this).serialize(),
                        success: function (data) {
                            if (data == "register-success") {
                                window.location = "login.php";
                            } else if (data == "username-fail") {
                                $("#username-error").css("display", "block");
                            } else if (data == "email-fail") {
                                $("#email-error").css("display", "block");
                            } else if (data == "password-confirm-fail") {
                                $("#password-confirm-error").css("display", "block");
                            } else if (data == "fields-fail") {
                                $("#form-error").css("display", "block");
                            }
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
<!-- Header -->
<?php include('templates/header.php'); ?>
<!-- Content -->
<main class="page register-page">
    <section class="portfolio-block contact">
        <div class="container">
            <div class="heading">
                <h2>Sign Up</h2>
            </div>
            <form id="register-form" class="register" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input class="form-control item" type="text" name="username" id="username" minlength="4"
                           maxlength="20">
                    <p id="username-error" class="form-group" style="display: none">Username is taken</p>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control item" type="password" name="password" id="password" minlength="8">
                    <p id="password-error" class="form-group" style="display: none">Password is not secure</p>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input class="form-control item" type="password" name="confirmPassword" id="confirm_password">
                    <p id="password-confirm-error" class="form-group" style="display: none">Passwords do not match</p>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control item" type="email" name="email" id="email">
                    <p id="email-error" class="form-group" style="display: none">Email is taken</p>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block btn-lg" type="submit">Create Account</button>
                    <p id="form-error" class="form-group" style="display: none">Please fill in all fields</p>
                </div>
            </form>
        </div>
    </section>
</main>
<!-- Footer -->
<?php include('templates/footer.php'); ?>
</body>
</html>
