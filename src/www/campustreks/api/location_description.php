<?php
include "../utils/connection.php";

session_start();
$_SESSION['ingame'] = true;

function getDescription($objectiveID){
	$conn = opencon();
	$sql = "SELECT `Direction` FROM `location` WHERE `objectiveID` = ".$objectiveID;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		return(($result->fetch_assoc())["Direction"]);
	}
}
echo getDescription($_GET['objectiveID']);
?>