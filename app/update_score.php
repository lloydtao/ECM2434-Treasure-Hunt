<?php
	$json_data = file_get_contents('hunt_sessions/' . $_POST["pin"] . '.json');
	$hunt_data = json_decode($json_data, true);
	$hunt_data["teams"][$_POST["team"]]["teaminfo"]["score"]-=$hunt_data["teams"][$_POST["team"]]["objectives"]["photo"][$_POST["submission"]]["score"];
	$hunt_data["teams"][$_POST["team"]]["objectives"]["photo"][$_POST["submission"]]["score"]=$_POST["score"];
	$hunt_data["teams"][$_POST["team"]]["teaminfo"]["score"]+=$_POST["score"];
	$json_data = json_encode($hunt_data);
	file_put_contents('hunt_sessions/' . $_POST["pin"] . '.json', $json_data);

	echo "update-score-success";
	return;
?>