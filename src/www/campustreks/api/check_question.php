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
    $result = $conn->query("SELECT `Answer` FROM `location` WHERE `ObjectiveID` ='$objectiveID'");

    if ($result->num_rows > 0) {
        $actualAnswer = $result->fetch_row()[0];
        if ($answer == $actualAnswer) {
            echo "correct";
        }
    } else {
        echo "incorrect";
    }
}

if (isset($_POST["objectiveID"]) && isset($_POST["answer"])) {
    checkQuestion($_POST["objectiveID"], $_POST["answer"]);
}
