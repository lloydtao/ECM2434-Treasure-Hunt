<?php
session_start();

include "utils/connection.php";
/**
 * Script for handling user registering asynchronously
 * @author Joseph Lintern
 */

/**
 *Removes whitespace, slashes and special characters from strings
 * @param string $data
 * @param mysqli $conn
 * @return string $data
 */
function makeSafe($data, $conn)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);

    return $data;
}


/**
 *Attempts to add a new user's data to the database
 * @param $conn
 */
function registerUser($conn)
{
    $username = $_SESSION["username"];
    //Checks there is something written in the email field and creates variables for them
    if (isset($_POST['currentPassword']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
        $current = makeSafe($_POST['currentPassword'], $conn);
        $password = makeSafe($_POST['password'], $conn);
        $cPassword = makeSafe($_POST['confirmPassword'], $conn);

        if (empty($current) || empty($password) || empty($cPassword)) {
            echo "fields-fail";
            return;
        }

        //Checks the user typed the passwords correctly
        if ($password == $cPassword) {
            //Selects all user data from the database
            $sql = "SELECT Password FROM users WHERE Username='$username'";
            $dbUser = mysqli_query($conn, $sql);

            if (mysqli_num_rows($dbUser) > 0) {
                while ($row = mysqli_fetch_assoc($dbUser)) {


                    $dbPass = $row['Password'];

                    //Validates that the email and username are unique
                    if (!(password_verify($current, $dbPass))) {
                        echo "current-fail";
                        return;
                    }
                }

            }
            //Hashes password and inputs user data to database

            $insert = "UPDATE Users SET Password=? WHERE Username='$username'";
            if($stmt = mysqli_prepare($conn, $insert)){
                mysqli_stmt_bind_param($stmt, "s", $pass);
                $pass = password_hash($password, PASSWORD_DEFAULT);
                mysqli_stmt_execute($stmt);

            }
            mysqli_stmt_close($stmt);
            echo "change-success";
        } else {
            echo "password-confirm-fail";
        }
    } else {
        echo "fields-fail";
    }
    $conn->close();
}

//Tries to input data once the submit button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registerUser(openCon());
}


?>
