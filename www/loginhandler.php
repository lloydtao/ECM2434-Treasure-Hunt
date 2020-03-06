<?php
include "utils/connection.php";
/**
 * Script for asynchronously handling login form data
 * @author Jakub Kwak
 */


/**
 * Sanitize string data
 *
 * Uses trim, stripslashes, htmlspecialchars and mysqli_real_escape_string to
 * sanitize the data
 *
 * @param string $data data to sanitize
 * @param mysqli $conn database connection
 * @return string sanitized data
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
 * Check if login credentials match an account in the database, and save data to session
 *
 * @param mysqli $conn database connection
 */
function loginUser($conn)
{
    $user = makeSafe($_POST["email"], $conn);
    $password = makeSafe($_POST["password"], $conn);

    // If input is an email address, check emails, otherwise check usernames
    if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
        $result = $conn->query("SELECT * FROM `users` WHERE `Email` = '$user'");
    } else {
        $result = $conn->query("SELECT * FROM `users` WHERE `Username` = '$user'");
    }


    $row = $result->fetch_assoc();
    if (password_verify($password, $row["Password"])) {
        // Save login info in session
        session_start();
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $row["Username"];
        echo("login-success");
    } else {
        echo("login-fail");
    }
    // Close connection
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    loginUser(openCon());
}
