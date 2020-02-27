<link rel="stylesheet" href="css/leaderboard_stylesheet.css">

<?php
if(isset($_GET['sessionID'])){
    $huntSessionID = $_GET['sessionID'];
    $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
	$hunt_session_data = json_decode($json_data, true);
}
$teams = $hunt_session_data['teams'];

$teamNames  = array_keys($teams);
$scores = array();
for ($i=1; $i<count($teams); $i++){
	array_push($scores, $teams[$teamNames[$i]]["teamInfo"]["score"]);
}
array_multisort($scores, SORT_DESC);

$lightRow = true;
for ($i=0; $i < count($scores); $i++) { 
	if($lightRow){
		echo '<div class="team-light">';
	}
	else{
		echo '<div class="team-dark">';
	}
	$lightRow = !$lightRow;
	echo '<div class="team-name">';
	echo $teamNames[$i+1];
	echo '</div>';
	echo '<div class="team-score">';
	echo $scores[$i];
	echo '</div>';
	echo '</div>';
}
?>
