<?php
include "utils/connection.php";

/**
 * Sends an email with recovery link and code to reset password
 * @param $email
 * @author Jakub Kwak
 */
function sendMail($email) {
    session_start();
    $code = str_pad(rand(0,999999), 6, '0', STR_PAD_LEFT);
    $_SESSION["recoveryCode"] = password_hash($code, PASSWORD_DEFAULT);
    $_SESSION["recoveryEmail"] = $email;

    $msg = "Campus Treks Password Recovery/n
    /n
    Password Recovery Code: ". $code ."/n
    /n
    Click the link below to reset your password:/n
    LINK HERE ONCE IN CLOUD";
    mail($email, "Campus Treks Password Recovery", $msg);
}

/**
 * Checks if email address is in database
 * @param $email
 * @return boolean
 * @author Jakub Kwak
 */
function checkEmail($email) {
    $conn = openCon();

    $sql = $conn->prepare("SELECT * FROM `users` WHERE `Email` = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();
    
    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    if (checkEMail($_POST["email"])) {
        sendMail($_POST["email"]);
    }
    header("Location: login.php");
}