<link rel="stylesheet" href="css/leaderboard_stylesheet.css">

<?php
if(isset($_GET['sessionID'])){
    $huntSessionID = $_GET['sessionID'];
    $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
	$hunt_session_data = json_decode($json_data, true);
}

$teamNames  = array_column($hunt_session_data['teams'], 'name');
$scores = array_column($hunt_session_data['teams'], 'score');

array_multisort($scores, SORT_DESC, $teamNames, SORT_ASC);

$lightRow = true;
for ($i=0; $i < count($teamNames); $i++) { 
	if($lightRow){
		echo '<div class="team-light">';
	}
	else{
		echo '<div class="team-dark">';
	}
	$lightRow = !$lightRow;
	echo '<div class="team-name">';
	echo $teamNames[$i];
	echo '</div>';
	echo '<div class="team-score">';
	echo $scores[$i];
	echo '</div>';
	echo '</div>';
}
?>