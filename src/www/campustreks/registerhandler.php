<?php
include "utils/connection.php";
/**
 * Script for handling user registering asynchronously
 * @author Joseph Lintern
 * @contributor Jakub Kwak
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
    //Checks there is something written in the email field and creates variables for them
    if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
        $email = makeSafe($_POST['email'], $conn);
        $username = makeSafe($_POST['username'], $conn);
        $password = makeSafe($_POST['password'], $conn);
        $cPassword = makeSafe($_POST['confirmPassword'], $conn);

        if (empty($email) || empty($username) || empty($password) || empty($cPassword)) {
            echo "fields-fail";
            return;
        }

        //Checks the user typed the passwords correctly
        if ($password == $cPassword) {
            //Selects all user data from the database
            $sql = "SELECT * FROM users";
            $dbUser = mysqli_query($conn, $sql);

            if (mysqli_num_rows($dbUser) > 0) {

                while ($row = mysqli_fetch_assoc($dbUser)) {
                    $dbEmail = $row['Email'];
                    $dbUsername = $row['Username'];
                    
                    //Validates that the email and username are unique
                    if (strtoupper($email) == strtoupper($dbEmail)) {
                        echo "email-fail";
                        return;
                    } elseif (strtoupper($username) == strtoupper($dbUsername)) {
                        echo "username-fail";
                        return;
                    }
                }
            }
            //Hashes password and inputs user data to database
            $pass = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (Email, Username, Password, Verified) 
            VALUES (?, ?, ?, 'False');");
            $insert->bind_param("sss", $email, $username, $pass);
            if(!$insert->execute()){
                echo "register-fail";
            }else{
                echo "register-success";
            }
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
