<?php 
/**
 * Script for handling team joining
 * @author James Caddock
 */

/**
 * Make data safe my stripping slashes, removing whitespace and special chars from string
 * @param $data
 * @return string
 */
function makeSafe($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Tries to create a team using POST data. Checks for duplicate team names and saves info to session.
 */
function joinTeam() 
{
    session_start(); 

    if (!isset($_POST['tableteam']) || !isset($_SESSION['gameID'])) {
        echo "no team or gameID";
        return;
    }

    // Gets pin and nickname from session
    $pin = $_SESSION['gameID'];
    $nickname = $_SESSION['nickname'];
    $team = makeSafe($_POST['tableteam']);
    

    //read and parse hunt json
    $filename = './hunt_sessions/' . $pin . '.json';
    $jsonString = file_get_contents($filename);
    $parsedJson = json_decode($jsonString, true);


    $counter = 0;
    $playerList = $parsedJson["teams"][""]["players"];
    $parsedJson["teams"][""]["players"] = [];
    foreach ($playerList as $player) {
        if (!(strtoupper($player) == strtoupper($nickname))) {
            array_push($parsedJson["teams"][""]["players"], $player);
            $counter += 1;
        }
    }

    $teamList = $parsedJson["teams"];
    $teamcounter = 0;
    foreach ($teamList as $key => $value) {
        //Check player list for duplicate names
        $playerList = $parsedJson["teams"][$key]["players"];
        foreach ($playerList as $player) {
            if (strtoupper($player) == strtoupper($nickname)) {
                echo "name-error";
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

    echo "join-team-success";
    header("location:play.php");
    return;
}

//if form was submitted, try to join game
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" ) {
    joinTeam();
} else {
    echo "form-error";
}
?>