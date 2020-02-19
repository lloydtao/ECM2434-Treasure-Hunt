<html>
  <head>
    <title>Register - CampusTreks</title>
	<?php include('templates/head.php'); ?>
  </head>
  <body>
	<!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
    <main class="page contact-page">
        <section class="portfolio-block contact">
            <div class="container">
                <div class="heading">
                    <h2>Sign Up</h2>
                </div>
                <form class="register" action="register.php" method="post">
                    <div class="form-group">
						<label for="username">Username</label>
						<input class="form-control item" type="text" id="username">
					</div>
                    <div class="form-group">
						<label for="password">Password</label>
						<input class="form-control item" type="password" id="password">
					</div>
                    <div class="form-group">
						<label for="confirm_password">Confirm Password</label>
						<input class="form-control item" type="password" id="confirm_password">
					</div>
                    <div class="form-group">
						<label for="email">Email</label>
						<input class="form-control item" type="email" id="email">
					</div>
                    <div class="form-group">
						<button class="btn btn-primary btn-block btn-lg" type="submit">Create Account</button>
					</div>
                </form>
            </div>
        </section>
    </main>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>

<?php
include "utils/connection.php";

/**
 *Removes whitespace, slashes and special characters from strings
 *@param string $data
 *@return string $data
*/
function makeSafe($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

/**
 *Attempts to add a new user's data to the database
 *@param $conn
*/
function registerUser($conn)
{
    //Checks there is something written in the email field and creates variables for them
    if(isset($_POST['Email']))
    {
        $email = makeSafe($_POST['Email']);
        $username = makeSafe($_POST['Username']);
        $password = makeSafe($_POST['Password']);
        $cPassword = makeSafe($_POST['ConfirmPassword']);

        //Checks the user typed the passwords correctly
        if($password == $cPassword)
        {
            //Selects all user data from the database
            $sql = "SELECT * FROM users";
            $dbUser = mysqli_query($conn, $sql);

            if (mysqli_num_rows($dbUser) > 0)
            {

                while($row = mysqli_fetch_assoc($dbUser))
                {
                    $dbEmail = $row['Email'];
                    $dbUsername = $row['Username'];
                    $dbPass = $row['Password'];

                    //Validates that the email and username are unique
                    if($email == $dbEmail)
                    {
                        echo "<script>";
                        echo "alert('Error: Email aready exists');";
                        echo "</script>";
                        return;
                    }elseif ($username == $dbUsername)
                    {
                        echo "<script>";
                        echo "alert('Error: Username already exists');";
                        echo "</script>";
                        return;
                    }
                }
            }
            //Hashes password and inputs user data to database
            $pass = password_hash($password, PASSWORD_DEFAULT);

            $insert = "INSERT INTO users (Email, Username, Password) 
                       VALUES ('$email', '$username', '$pass')";
            $result = mysqli_query($conn, $insert);

            echo "<script>";
            echo "alert('User created successfully');";
            echo "</script>";
        }else
        {
            echo "<script>";
            echo "alert('Error: Passwords do not match');";
            echo "</script>";
        }
    }
    echo "<script>";
    echo "alert('Error: Must fill in all fields');";
    echo "</script>";
}

//Tries to input data once the submit button is pressed
if(isset($_POST['Submit']))
{
   registerUser(openCon());
}
 ?>
