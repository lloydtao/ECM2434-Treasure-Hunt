<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

    <form class="register" action="register.php" method="post">
      Email:<br>
      <input type="email" name="Email" required><br>
      Username:<br>
      <input type="text" name="Username" required><br>
      Password:<br>
      <input type="password" name="Password" required><br>
      Confirm Password:<br>
      <input type="password" name="ConfirmPassword" required><br>
      <input type="submit" name="Submit" value="submit">

    </form>

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
