<?php
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
