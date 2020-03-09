<?php
include "utils/connection.php";

/**
 * Checks the GET data for password recovery
 *
 * @author Jakub Kwak
 */
function checkFormData() {
    if (!isset($_POST["email"]) || !isset($_POST["recoveryCode"]) || !isset($_POST["password"]) || !isset($_POST["confirmPassword"])) {
        echo "form-error";
        return;
    }
    $email = $_POST["email"];
    $recoveryCode = $_POST["recoveryCode"];
    $password = $_POST["password"];
    $cPassword = $_POST["confirmPassword"];

    if ($email == "" || $recoveryCode == "" || $password == "" || $cPassword == "") {
        echo "form-error";
        return;
    }

    if ($password != $cPassword) {
        echo "password-error";
        return;
    }

    session_start();
    if (!isset($_SESSION["recoveryCode"]) || !isset($_SESSION["recoveryEmail"])) {
        echo "session-error";
        return;
    }
    if ($_SESSION["recoveryEmail"] != $email) {
        echo "reset-error";
        return;
    }

    if (!password_verify($recoveryCode, $_SESSION["recoveryCode"])) {
        echo "reset-error";
        return;
    }

    resetPassword($email, $password);
}

/**
 * Changes the password in the database
 * @param $email
 * @param $password
 */
function resetPassword($email, $password) {
    $conn = openCon();
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);


    $sql = $conn->prepare("SELECT * FROM `users` WHERE `Email` = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows == 0) {
        echo "reset-error";
    }

    $sql = $conn->prepare("UPDATE `users` SET `Password` = ? WHERE `Email` = ?");
    $sql->bind_param("ss", $passwordHash, $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result) {
        echo "success";
    } else {
        echo "reset-error";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    checkFormData();
}
