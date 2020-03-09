<?php
include "../utils/connection.php";

/**
 * sanitize data
 * @param $data
 * @param $conn
 * @return string
 * @author Jakub Kwak
 */
function makeSafe($data, $conn)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

/**
 * compare answer with database for a given location objective
 * @param $objectiveID
 * @param $answer
 * @author Jakub Kwak
 */
function checkQuestion($objectiveID, $answer) {
    $conn = openCon();
    $stmt = $conn->prepare("SELECT `Answer` FROM `location` WHERE `ObjectiveID` ='?'");
    $stmt->bind_param("i", $objectiveID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $actualAnswer = $result->fetch_row()[0];
        if (strtolower($answer) == strtolower($actualAnswer)) {
            updateObjective();
            echo "correct";
        }
        else {
            echo "incorrect";
        }
    } 
    $stmt->close();
}

if (isset($_GET["objectiveKey"]) && isset($_GET["objectiveID"]) && isset($_GET["answer"]) && isset($_GET["teamName"]) && isset($_GET["gameID"])) {
    checkQuestion($_GET["objectiveID"], $_GET["answer"]);
}

/**
 * update objective to completed in json
 */
function updateObjective()
{
    //get post data
    $objID = $_GET["objectiveKey"];
    $teamName = $_GET["teamName"];
    $pin = $_GET["gameID"];

    //get json
    $filename = '../hunt_sessions/' . $pin . '.json';
    $jsonString = file_get_contents($filename);
    $parsedJson = json_decode($jsonString, true);

    //update objective
    $parsedJson["teams"][$teamName]["objectives"]["gps"][$objID]["completed"] = true;
    $parsedJson["teams"][$teamName]["teaminfo"]["score"] += 100;

    //update json file
    $newJson = json_encode($parsedJson);
    file_put_contents($filename, $newJson);
}
