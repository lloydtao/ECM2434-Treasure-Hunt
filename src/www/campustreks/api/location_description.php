<?php
include "../utils/connection.php";

session_start();
$_SESSION['ingame'] = true;

/**
 * Get objective description/direction
 * @param $objectiveID
 * @return mixed
 */
function getDescription($objectiveID){
	$conn = opencon();
	$sql = "SELECT `Direction` FROM `location` WHERE `ObjectiveID` = ".$objectiveID;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		return(($result->fetch_assoc())["Direction"]);
	}
}
echo getDescription($_GET['objectiveID']);
?>