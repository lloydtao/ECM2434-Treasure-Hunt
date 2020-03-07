<?php

/**
 * 
 * @author James Caddock
 */
function endSession() 
{
    session_start();

    // Gets pin and nickname from session
    $pin = $_SESSION['gameID'];
    $nickname = $_SESSION['nickname'];

    if ($pin != null) {
        //read and parse hunt json
        $filename = '../hunt_sessions/' . $pin . '.json';
        $jsonString = file_get_contents($filename);
        $parsedJson = json_decode($jsonString, true);

        $counter = 0;
        $oldteam = "";
        if (isset($_SESSION["teamName"])) {
            $oldteam = $_SESSION["teamName"];
        }
        $playerList = $parsedJson["teams"][$oldteam]["players"];
        $parsedJson["teams"][$oldteam]["players"] = [];
        foreach ($playerList as $player) {
            if (!(strtoupper($player) == strtoupper($nickname))) {
                array_push($parsedJson["teams"][$oldteam]["players"], $player);
                $counter += 1;
            } else {
                $found = true;
            }
        }

        //update json file
        $newJson = json_encode($parsedJson);
        file_put_contents($filename, $newJson);
    }
    
    unset($_SESSION['nickname']);
    unset($_SESSION['teamName']);
    session_destroy();

    echo "session-ended";
}


//if form was submitted, try to join game
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" ) {
    endSession();
} else {
    echo "form-error";
}
?>