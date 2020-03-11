<?php
/**
 * API for fetching location data for a location objective in the data base
 * @author Marek Tancak
 */
include "../utils/connection.php";

session_start();
$_SESSION['ingame'] = true;

/**
 * Get the directions from the SQL database
 * @param $objectiveID
 * @author Marek Tancak
 */
function getDescription($objectiveID){
	$conn = opencon();
	$sql = "SELECT `Direction` FROM `location` WHERE `objectiveID` = ".$objectiveID;
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		return(($result->fetch_assoc())["Direction"]);
	}
}

if (isset($_GET["objectiveID"])) {
	echo getDescription($_GET['objectiveID']);
}
?>