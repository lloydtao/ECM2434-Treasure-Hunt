<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Joseph Lintern">
        <title>Account</title>
        <?php include('templates/head.php');

        include "checklogin.php";
        if (!CheckLogin()) {
            header("location:login.php");
        }

        include "utils/connection.php";
        $conn = openCon();

        /**
         *Displays the username, email, verification status and created hunts of the logged in the user
         * @param mysqli $conn
         */
        function displayInfo($conn){
            //Stores username of user currently logged in
            $username = $_SESSION["username"];

            //Retrieves information of the user from the database
            $sql = "SELECT * FROM Users WHERE Username=?";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $data = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($data)) {

                    $email = $row['Email'];
                    $verified = $row['Verified'];
                }
            }
            mysqli_stmt_close($stmt);

          //Displays information of the user
          echo "Username: ". $username . "<br>";
          echo "Email: ". $email . "<br>";
          if($verified == 1){
              echo "Verified <img src='img/exeter-logo.png' height='14px' width='14px'> <br>";
          }else {
              echo "Not verified <br>";
          }

          //Displays hunts created by the user in a table
          $huntSQL = "SELECT Name, Description, BestTeam, HighScore FROM hunt WHERE Username=?";
          $stmt = mysqli_stmt_init($conn);

          if(mysqli_stmt_prepare($stmt, $huntSQL)){
              mysqli_stmt_bind_param($stmt, "s", $username);
              mysqli_stmt_execute($stmt);
              $huntData = mysqli_stmt_get_result($stmt);

              echo "<table class='huntTable'>";
              echo "<tr><th>Name</th><th>Description</th><th>Best Team</th><th>High Score</th></tr>";

              while ($row = mysqli_fetch_assoc($huntData)) {

                  $name = htmlspecialchars($row['Name']);
                  $description = htmlspecialchars($row['Description']);
                  $bestTeam = htmlspecialchars($row['BestTeam']);
                  $highScore = htmlspecialchars($row['HighScore']);

                  echo "<tr><td>".$name."<td>".$description."</td><td>".$bestTeam."</td><td>".$highScore."</tr>";
              }
              echo "</table>";
          }
          mysqli_stmt_close($stmt);

        }
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="js/nbp/nbp_es6.js"></script>
        <script src="js/testPassword.js"></script>
        <script>
            $(document).ready(function () {
                //initialise NBP with password list
                NBP.init("mostcommon_1000000", "collections/", true);
                $("#register-form").submit(function (e) {
                    e.preventDefault();
                    $("#current-error").css("display", "none");
                    $("#password-confirm-error").css("display", "none");
                    $("#form-error").css("display", "none");
                    $("#password-error").css("display", "none");
                    $("#change-display").css("display", "none");
                    if (!testPassword($('input[name=password]').val())) {
                        $("#password-error").css("display", "block");
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "change_password_handler.php",
                            data: $(this).serialize(),
                            success: function (data) {
                                if (data == "change-success") {
                                    $("#change-display").css("display", "block");
                                } else if (data == "current-fail") {
                                    $("#current-error").css("display", "block");
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
                        <?php displayInfo($conn); ?>
                    </div>
                    <form id="register-form" class="register" method="post">
                        <h2>Change Password</h2>

                        <div class="form-group">
                            <label for="password">Current Password</label>
                            <input class="form-control item" type="password" name="currentPassword" id="currentPassword">
                            <p id="current-error" class="form-group" style="display: none">Incorrect Password</p>
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input class="form-control item" type="password" name="password" id="password" minlength="8">
                            <p id="password-error" class="form-group" style="display: none">Password is not secure</p>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input class="form-control item" type="password" name="confirmPassword" id="confirm_password" minlength="8">
                            <p id="password-confirm-error" class="form-group" style="display: none">Passwords do not match</p>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block btn-lg" type="submit">Change Password</button>
                            <p id="form-error" class="form-group" style="display: none">Please fill in all fields</p>
                            <p id="change-display" class="form-group" style="display: none">Password changed successfully</p>
                        </div>
                    </form>
                </div>
            </section>
        </main>


        <?php include('templates/footer.php'); ?>
    </body>
</html>
