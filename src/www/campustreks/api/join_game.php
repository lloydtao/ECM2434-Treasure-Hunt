<?php
/**
 * Script for handling game join form
 * @author Jakub Kwak
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
 * Attempt to find a game session file with given pin, if a file is found, saves the pin to session and returns true.
 * @param $pin
 * @return boolean
 */
function findGame($pin)
{
    $filename = '../hunt_sessions/' . $pin . '.json';
    if (file_exists($filename))
    {
        return true;
    } else {
        return false;
    }
}

/**
 * Attempt to join a game based on POST data. Checks for duplicate player names and saves info to session.
 */
function joinGame()
{
    if (!isset($_POST['pin']) || !isset($_POST['nickname'])) {
        echo "form-error";
        return;
    }
    $pin = makeSafe($_POST['pin']);
    $nickname = makeSafe($_POST['nickname']);

    //if either field is empty, echo error
    if (empty($pin) || empty($nickname)) {
        echo "form-error";
        return;
    }

    if (findGame($pin))
    {
        //read and parse hunt json
        $filename = '../hunt_sessions/' . $pin . '.json';
        $jsonString = file_get_contents($filename);
        $parsedJson = json_decode($jsonString, true);

        //Check player list for duplicate names
        $teamList = $parsedJson["teams"];
        foreach ($teamList as $t) {
            $playerList = $t["players"];
            foreach ($playerList as $player) {
                if (strtoupper($player) == strtoupper($nickname)) {
                    echo "name-error";
                    return;
                }
            }
        }

        //insert player into json data
        array_push($parsedJson["teams"][""]["players"], $nickname);

        //update json file
        $newJson = json_encode($parsedJson);
        file_put_contents($filename, $newJson);

        //put hunt data in session
        session_start();
        $_SESSION['gameID'] = $pin;
        $_SESSION['nickname'] = $nickname;

        echo "join-success";
        return;
    } else {
        echo "pin-error";
        return;
    }
}

//if form was submitted, try to join game
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" ) {
    joinGame();
} else {
    echo "form-error";
}
