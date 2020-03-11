<?php 
/**
 * Script for handling team creation form
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
function createTeam() 
{
    session_start(); 

    if (!isset($_SESSION['gameID'])) {
        echo "session-error";
        return;
    }
    $team = makeSafe($_POST['newteam']);

    //if either field is empty, echo error
    if (empty($team)) {
        echo "team-form-error";
        return;
    }

	//if either field is empty, echo error
    if (empty($team)) {
        echo "team-form-error";
        return;
    }
	
    // Gets pin and nickname from session
    $pin = $_SESSION['gameID'];
    $nickname = $_SESSION['nickname'];

    //read and parse hunt json
    $filename = '../hunt_sessions/' . $pin . '.json';
    $jsonString = file_get_contents($filename);
    $parsedJson = json_decode($jsonString, true);

    //Check teams for duplicate team names
    $teamList = $parsedJson["teams"];
    foreach ($teamList as $key => $t) {
        if (strtoupper($key) == strtoupper($team)) {
            echo "team-error";
            return;
        }
    }

    // Creates and adds player to new team
    $teamCount = count($teamList);
    $parsedJson["teams"][$team]["teaminfo"]["score"] = "0";
    $parsedJson["teams"][$team]["players"] = [];
    array_push($parsedJson["teams"][$team]["players"], $nickname);
    $parsedJson["teams"][$team]["objectives"] = [];
    

    // Removes player from old / default team
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
    require("createteamobjectives.php");
    $parsedJson = addTeamObjectives($parsedJson, $team);
	
	//check if team is left empty and should be deleted
    if (count($parsedJson["teams"][$oldteam]["players"]) == 0 && $oldteam != "") {
        unset($parsedJson["teams"][$oldteam]);
    }

    //update json file
    $newJson = json_encode($parsedJson);
    file_put_contents($filename, $newJson);

    $_SESSION["teamName"] = $team;

    echo "create-team-success";
    return;
}

//if form was submitted, try to join game
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" ) {
    createTeam();
} else {
    echo "team-form-error";
}
?>