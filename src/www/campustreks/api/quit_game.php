<?php

/**
 * 
 * @author James Caddock
 */
function quitGame() 
{
    session_start();

    if (!(isset($_SESSION['gameID'])) || !(isset($_SESSION['nickname']))) {
        unset($_SESSION['game']);
        echo "game-ended"; 
        return;
    } 
    else {

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

                //check if team is left empty and should be deleted
                if (count($parsedJson["teams"][$oldteam]["players"]) == 0 && $oldteam != "") {
                    unset($parsedJson["teams"][$oldteam]);
                }


                //update json file
                $newJson = json_encode($parsedJson);
                file_put_contents($filename, $newJson);
            }

            if (isset($_SESSION['game'])) {
                unset($_SESSION['game']);
            }

            unset($_SESSION['nickname']);
            unset($_SESSION['gameID']);
            unset($_SESSION['teamName']);

            echo "game-ended";
    }
}


//if form was submitted, try to join game
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" ) {
    quitGame();
} else {
    echo "form-error";
}
?>