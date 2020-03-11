<?php
/**
 * Script for ending a hunt and updating highscore in the database
 * @author Jakub Kwak
 */
include "../utils/connection.php";

if (isset($_SESSION['Username']) && isset($_REQUEST['gameID']) && isset($_REQUEST['highscore']) && isset($_REQUEST['teamName']) ) {
    //get Hunt ID
    $jsonData = file_get_contents('../hunt_sessions/' . $_REQUEST['gameID'] . '.json');
    if ($_SESSION['Username'] == json_decode($jsonData, true)["gameinfo"]["master"]){
        $huntID = json_decode($jsonData, true)["gameinfo"]["huntID"];

        //close hunt
        if (endHunt($_REQUEST['gameID'], $huntID)) {
            //update highscore
            compareHighscore($huntID, $_REQUEST['highscore'], $_REQUEST['teamName']);
        } else {
            errorResponse('Could not end hunt');
        }
    }
    else{
        errorResponse('Unauthorised');
    }
} else {
    errorResponse('Invalid request');
}

/**
 * echo error response
 * @param string $error
 */
function errorResponse(string $error)
{
    echo json_encode([
        'status' => 'error',
        'message' => $error,
    ]);
    exit;
}

/**
 * echo success response
 * @param string $message
 */
function successResponse(string $message)
{
    echo json_encode([
        'status' => 'ok',
        'message' => $message,
    ]);
    exit;
}

/**
 * Compare the highscore with highscore in the database
 * @param $huntID
 * @param $highscore
 * @param $teamName
 */
function compareHighscore($huntID, $highscore, $teamName)
{
    $conn = openCon();

    $sql = $conn->prepare("SELECT `Highscore` FROM `Hunt` WHERE `HuntID` = ?");
    $sql->bind_param('i', $huntID);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    if ($row > 0) {
        if ($row['Highscore'] == null) {
            //if current highscore does not exist
            updateHighscore($huntID, $highscore, $teamName, $conn);
        } else if ($row['Highscore'] < $highscore) {
            //if current highscore is lower than new highscore
            updateHighscore($huntID, $highscore, $teamName, $conn);
        } else {
            $conn->close();
            successResponse('Highscore unchanged');
        }
    } else {
        $conn->close();
        errorResponse('Hunt not found');
    }
}

/**
 * update the highscore and best team in the database
 * @param $huntID
 * @param $highscore
 * @param $teamName
 * @param $conn
 */
function updateHighscore($huntID, $highscore, $teamName, $conn)
{
    $sql = $conn->prepare("UPDATE `Hunt` SET `Highscore` = ?, `BestTeam` = ? WHERE `HuntID` = ?");
    $sql->bind_param('isi', $highscore, $teamName, $huntID);
    if ($sql->execute()) {
        $conn->close();
        successResponse('Highscore updated');
    } else {
        $conn->close();
        errorResponse('Update unsuccessful');
    }
}

/**
 * end the hunt by storing the json in the database and then deleting it
 * @param $gameID
 * @param $huntID
 * @return bool success
 */
function endHunt($gameID, $huntID)
{
    $conn = openCon();
    $jsonData = file_get_contents('../hunt_sessions/' . $gameID . '.json');

    $sql = $conn->prepare("INSERT INTO `huntdata` (`HuntID`, `json`) VALUES (?, ?)");
    $sql->bind_param("is", $huntID, $jsonData);
    if ($sql->execute()) {
        unlink('../hunt_sessions/' . $gameID . '.json');
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}