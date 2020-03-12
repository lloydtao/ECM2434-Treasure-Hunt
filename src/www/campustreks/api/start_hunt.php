<?php
function generateGamePin()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomPIN = '';

    for ($i = 0; $i < 4; $i++) {
        $randomPIN .= $characters[rand(0, (strlen($characters)) - 1)];
    }

    $files = scandir('../hunt_sessions');
    if (in_array($randomPIN . '.json', $files)) {
        $randomPIN = generateGamePin();
    }

    return $randomPIN;
}

include "../checklogin.php";
if (!CheckLogin()) {
    header("location:login.php");
}

if (!is_dir("../hunt_sessions/")) {
    mkdir("../hunt_sessions");
}

$user = $_SESSION["username"];
$huntID = $_POST['huntID'];
$gamePIN = generateGamePin();
$_SESSION["hostGameID"] = $gamePIN;
$huntSession = array('gameinfo' => array('gamePin' => $gamePIN, 'huntID' => $huntID, 'master' => $user),
    'teams' => array('' => array('teaminfo' => array(), 'players' => array(), 'objectives' => json_decode("{}"))));
$json_data = json_encode($huntSession);
file_put_contents('../hunt_sessions/' . $gamePIN . '.json', $json_data);

echo "start-hunt-success";
return;
?>