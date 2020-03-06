<?php
include "../utils/connection.php";

function getDescription($huntID){
	$conn = opencon();
	$sql = "SELECT `Description` FROM `hunt` WHERE `HuntID` = ".$huntID;	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		return(($result->fetch_assoc())["Description"]);
	}
}
echo getDescription($_GET['huntID']);
?>