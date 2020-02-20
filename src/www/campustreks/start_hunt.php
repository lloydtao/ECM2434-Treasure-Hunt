<?php
	function generateGamePin() {
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomPIN = '';
	    for ($i = 0; $i < 10; $i++) {
	        $randomPIN .= $characters[rand(0, (strlen($characters)) - 1)];
	    }
	    return $randomPIN;
	}

	$huntID = $_GET['huntID'];
	$huntSessionID = 5678;
	$gamePIN = generateGamePin();
	$huntSession = array('gamePIN'=> $gamePIN, 'huntID'=>$huntID, 'teams'=>array());
	$json_data = json_encode($huntSession);
	file_put_contents('hunt_sessions/' . $huntSessionID . '.json', $json_data);

	header('Location: /hunt.php?sessionID=' . $huntSessionID);
	die();
?>