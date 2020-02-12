<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "campustreks";
$email = "";
$password = "";
$error = "";

session_start();
// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "<br>");
}
// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = Sanitize($_POST["email"], $conn);
  $password = Sanitize($_POST["password"], $conn);
}
if (CheckCredentials($email, $password, $conn)) {
  // Save login info in session
  $_SESSION['isLoggedIn'] = true;
  $_SESSION['email'] = $email;
  $_SESSION['username'] = $row["Username"];
  header("Location: home.php");
} else {
  // Return to login page with error
  header("location:login.php?loginFailed=true");
}
// Close connection
$conn->close();

/**
 * Sanitize string data
 *
 * Uses trim, stripslashes, htmlspecialchars and mysqli_real_escape_string to
 * sanitize the data
 *
 * @param string data to sanitize
 * @param mysqli databse connection
 * @return string sanitized data
 */
function Sanitize($data, $conn) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = $conn->real_escape_string($data);
  return $data;
}

/**
 * Check if login credentials match an account in the database
 *
 * @param string account email
 * @param string unhashed password
 * @param mysqli database connection
 * @return boolean true if login information is correct, otherwise false
 */
function CheckCredentials($email, $password, $conn) {
  // Get account with matching email from database
  $result = $conn->query("SELECT * FROM `users` WHERE `email` = '$email'");
  if (!$result) {
    return false;
  } else {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["PasswordHash"])) {
      return true;
    } else {
      return false;
    }
  }
}
?>
