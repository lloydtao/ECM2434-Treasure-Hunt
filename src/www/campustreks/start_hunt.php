<meta name="author" content="Marek Tancak">
<?php

/**
 * Generate a unique 4 char long pin for the new hunt session
 */
function generateGamePin()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomPIN = '';

    for ($i = 0; $i < 4; $i++) {
        $randomPIN .= $characters[rand(0, (strlen($characters)) - 1)];
    }

    $files = scandir('./hunt_sessions');
    if (in_array($randomPIN . '.json', $files)) {
        $randomPIN = generateGamePin();
    }

    return $randomPIN;
}

include "checklogin.php";
if (!CheckLogin()) {
    header("location:login.php");
}

// Create the hunt sessions directory if it does not already exist
if (!is_dir("hunt_sessions/")) {
    mkdir("hunt_sessions");
}

$user = $_SESSION["username"];
$huntID = $_GET['huntID'];
$gamePIN = generateGamePin();
// Create the JSON file to store hunt data
$huntSession = array('gameinfo' => array('gamePin' => $gamePIN, 'huntID' => $huntID, 'master' => $user),
    'teams' => array('' => array('teaminfo' => array(), 'players' => array(), 'objectives' => json_decode("{}"))));
$json_data = json_encode($huntSession);
file_put_contents('hunt_sessions/' . $gamePIN . '.json', $json_data);

header('Location: /hunt_session.php?sessionID=' . $gamePIN);
die();
?>