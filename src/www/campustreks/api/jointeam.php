<?php
/**
 * Script for handling team joining
 * @author James Caddock
 */

/**
 * Tries to create a team using POST data. Checks for duplicate team names and saves info to session.
 */
function joinTeam()
{
    session_start();

    if (!isset($_POST['chosenteam']) || !isset($_SESSION['gameID'])) {
        echo "no team or gameID";
        return;
    }

    // Gets pin and nickname from session
    $pin = $_SESSION['gameID'];
    $nickname = $_SESSION['nickname'];
    $team = $_POST['chosenteam'];


    //read and parse hunt json
    $filename = './hunt_sessions/' . $pin . '.json';
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
        }
    }

    //check if team is left empty and should be deleted
    if (count($parsedJson["teams"][$oldteam]["players"]) == 0 && $oldteam != "") {
        unset($parsedJson["teams"][$oldteam]);
    }


    $teamList = $parsedJson["teams"];
    $teamcounter = 0;
    foreach ($teamList as $key => $value) {
        //Check player list for duplicate names
        $playerList = $parsedJson["teams"][$key]["players"];
        foreach ($playerList as $player) {
            if (strtoupper($player) == strtoupper($nickname)) {
                echo "duplicate name";
                return;
            }
        }

        if ($key == $team) {
            $teamplayerlist = $value["players"];
            $teamplayercount = count($teamplayerlist);
            array_push($parsedJson["teams"][$key]["players"], $nickname);
        }
        $teamcounter += 1;
    }


    //update json file
    $newJson = json_encode($parsedJson);
    file_put_contents($filename, $newJson);

    $_SESSION["teamName"] = $team;

    echo "join-team-success";
    return;
}

//if form was submitted, try to join game
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    joinTeam();
} else {
    echo "form-error";
}
?>