<?php
/**
 * Update location objective in the JSON, marking it as complete
 *
 * @author Jakub Kwak
 */

if (isset($_POST["objID"]) && isset($_POST["teamName"]) && isset($POST["gameID"])) {

}
/**
 * update objective to completed in json
 */
function updateObjective()
{
    //get post data
    $objID = $_POST["objID"];
    $teamName = $_POST["teamName"];
    $pin = $_POST["gameID"];

    //get json
    $filename = './hunt_sessions/' . $pin . '.json';
    $jsonString = file_get_contents($filename);
    $parsedJson = json_decode($jsonString, true);

    //update objective
    $parsedJson["teams"][$teamName]["objectives"][$objID]["completed"] = true;

    //update json file
    $newJson = json_encode($parsedJson);
    file_put_contents($filename, $newJson);
}
