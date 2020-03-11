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
 * function for adding objectives to a created team in parsed json
 *
 * @param array $parsedJson
 * @param string $teamName
 * @return array
 *
 * @author Jakub Kwak
 */
function addTeamObjectives($parsedJson, $teamName) {
    $huntID = $parsedJson["gameinfo"]["huntID"];

    include "../utils/connection.php";
    $conn = openCon();

    //fetch all objective IDS for this hunt from db
    $stmt = $conn->prepare("SELECT `ObjectiveID` FROM `objectives` WHERE `HuntID` = ?");
    $stmt->bind_param("i", $huntID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $objectiveIDs = array();
    while ($row = $result->fetch_row()) {
        $objectiveIDs[] = $row[0];
    }
    //fetch all location objectives for this hunt from db
    $stmt = $conn->prepare("SELECT * FROM `location` WHERE `ObjectiveID` = ?");
    $stmt->bind_param("i", $objectiveID);
    $locations = array();
    foreach($objectiveIDs as $objectiveID){
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row['ObjectiveID'] != null) {
            $locations[] = $row;
        }
    }
    //fetch all photo objectives for this hunt from db
    $stmt = $conn->prepare("SELECT * FROM `photoops` WHERE `ObjectiveID` = ?");
    $stmt->bind_param("i", $objectiveID);
    $photos = array();
    foreach($objectiveIDs as $objectiveID){
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row['ObjectiveID'] != null) {
            $photos[] = $row;
        }
    }

    $locations = randomStarting($locations);//randomise starting objective
    $objectiveIndex = 0;

    //add location objectives to json
    foreach ($locations as $location) {
        $jsonData = array(
            "completed" => false,
            "objectiveId" => $location["ObjectiveID"]);

        $parsedJson["teams"][$teamName]["objectives"]["gps"]["objective".$objectiveIndex] = $jsonData;
        $objectiveIndex++;
    }

    //add photo objectives to json
    foreach ($photos as $photo) {
        $jsonData = array(
            "completed" => false,
            "objectiveId" => $photo["ObjectiveID"],
            "description" => $photo["Specification"],
            "path" => null,
            "score" => 0);

        $parsedJson["teams"][$teamName]["objectives"]["photo"]["objective".$objectiveIndex] = $jsonData;
        $objectiveIndex++;
    }

    return $parsedJson;
}

/**
 *  Randomise the starting location objective
 *
 * @param array $locations
 * @return array
 *
 * @author Jakub Kwak
 */
function randomStarting($locations) {
    $startIndex = array_rand($locations, 1);
    $newLocations = array();
    $count = count($locations);

    for ($x = 0; $x < $count; $x++) {
        $index = $x + $startIndex;
        if ($index >= $count) {
            $index -= $count;//cycle back to start if reached end of list
        }
        $newLocations[] = $locations[$index];
    }

    return $newLocations;
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