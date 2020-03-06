<?php
/**
 * API for fetching question data from a location objective in the data base
 * @author Jakub Kwak
 */
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
 * Query database for question from location objective
 * @param $objectiveID
 * @author Jakub Kwak
 */
function getQuestion($objectiveID)
{
    $conn = openCon();
    $result = $conn->query("SELECT `Question` FROM `location` WHERE `ObjectiveID` ='$objectiveID'");

    if ($result->num_rows > 0) {
        $question = $result->fetch_row()[0];
        echo $question;
    } else {
        echo null;
    }
}

if (isset($_POST["objectiveID"])) {
    getQuestion(makeSafe($_POST["objectiveID"], $conn));
}