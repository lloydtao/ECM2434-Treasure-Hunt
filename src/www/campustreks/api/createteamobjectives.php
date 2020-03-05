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
    $result = $conn->query("SELECT `ObjectiveID` FROM `objectives` WHERE `HuntID` = '$huntID'");
    $objectiveIDs = array();
    while ($row = $result->fetch_row()) {
        $objectiveIDs[] = $row[0];
    }
    //fetch all location objectives for this hunt from db
    $result = $conn->query("SELECT * FROM `location` WHERE `ObjectiveID` IN (".implode(',',$objectiveIDs).")");
    $locations = array();
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
    //fetch all photo objectives for this hunt from db
    $result = $conn->query("SELECT * FROM `photoops` WHERE `ObjectiveID` IN (".implode(',',$objectiveIDs).")");
    $photos = array();
    while ($row = $result->fetch_assoc()) {
        $photos[] = $row;
    }

    $locations = randomStarting($locations);//randomise starting objective
    $objectiveIndex = 0;

    //add location objectives to json
    foreach ($locations as $location) {
        $jsonData = array(
            "type" => "gps",
            "completed" => false,
            "objectiveId" => $location["ObjectiveID"]);

        $parsedJson["teams"][$teamName]["objectives"]["objective".$objectiveIndex] = $jsonData;
        $objectiveIndex++;
    }

    //add photo objectives to json
    foreach ($photos as $photo) {
        $jsonData = array(
            "type" => "photo",
            "completed" => false,
            "objectiveId" => $photo["ObjectiveID"],
            "path" => null,
            "score" => 0);

        $parsedJson["teams"][$teamName]["objectives"]["objective".$objectiveIndex] = $jsonData;
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
