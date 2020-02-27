<meta name="author" content = "Marek Tancak">
<?php
	function generateGamePin() {
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomPIN = '';
	    
	    for ($i = 0; $i < 4; $i++) {
	        $randomPIN .= $characters[rand(0, (strlen($characters)) - 1)];
	    }

	    $files = scandir('./hunt_sessions');
	    if (in_array($randomPIN.'.json', $files)){
	    	$randomPIN = generateGamePin();
	    }
	    return $randomPIN;
	}

	$huntID = $_GET['huntID'];
	$gamePIN = generateGamePin();
	$huntSession = array('gameinfo'=> array('gamePin' => $gamePIN, 'huntID'=>$huntID), 
		'teams'=>array('' => array('teamInfo' => array(), 'players' => array(), 'objectives' => json_decode ("{}"))));
	$json_data = json_encode($huntSession);
	file_put_contents('hunt_sessions/' . $gamePIN . '.json', $json_data);

	header('Location: /hunt_session.php?sessionID=' . $gamePIN);
	die();
?>