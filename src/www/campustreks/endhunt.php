<?php
	if(isset($_GET['pin'])){
		include("utils/connection.php");
		$conn = openCon();
		$jsonData = file_get_contents('hunt_sessions/' . $_GET['pin'] . '.json');
		$huntData = json_decode($jsonData, true);
		$sql = "INSERT INTO `huntdata` (`JsonID`, `HuntID`, `reg_date`, `json`) VALUES (NULL, ".$huntData["gameinfo"]["huntID"].", current_timestamp(), '".$jsonData."')";
		if ($conn->multi_query($sql) === TRUE) {
        	echo "json uploaded";
        	unlink('hunt_sessions/' . $_GET['pin'] . '.json');
	    } else {
	        echo "error uploading json: " . $conn->error . "<br>";
	    }

		$conn->close();
	}

?>