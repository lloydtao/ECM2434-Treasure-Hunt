<?php
/**
 * Opens session and gets the user email
 *
 * @return string the user's email
 */
function getUser($conn)
{
    session_start();
    $user = $_SESSION['username'];
	if (!filter_var($user, FILTER_VALIDATE_EMAIL)){
        $result = $conn->query("SELECT `Email` FROM `users` WHERE `Username` = '$user'");
		$user = mysqli_fetch_assoc($result)["Email"];
	}
	return $user;
}
?>