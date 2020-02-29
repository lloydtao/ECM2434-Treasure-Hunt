<?php
/**
 * Check if a hunt session exists with this pin
 * @param $pin
 * @return bool
 *
 * @author Jakub Kwak
 */
function findGame($pin)
{
    $filename = './hunt_sessions/' . $pin . '.json';
    if (file_exists($filename))
    {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if the game in session still exists, and echo it back if it does
 *
 * @author Jakub Kwak
 */
function CheckGame()
{
    session_start();
    if (isset($_SESSION['gameID']) && isset($_SESSION['nickname'])) {
        $gameID = $_SESSION['gameID'];
        $nickname = $_SESSION['nickname'];
        if (findGame($gameID)) {
            if (isset($_SESSION['teamName'])) {
                $teamName = $_SESSION['teamName'];
                echo json_encode(array("status" => "success", "gameID" => $gameID, "nickname" => $nickname, "teamName" => $teamName));
            } else {
                echo json_encode(array("status" => "success", "gameID" => $gameID, "nickname" => $nickname, "teamName" => null));
            }
            echo json_encode(array("status" => "success", "gameID" => $gameID, "nickname" => $nickname));
            return;
        }
        unset($_SESSION['gameID']);
        unset($_SESSION['nickname']);
        echo json_encode(array("status" => "fail", "gameID" => null, "nickname" => null));
    }
}

CheckGame();