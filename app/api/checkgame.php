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
    $filename = '../hunt_sessions/' . $pin . '.json';
    if (file_exists($filename))
    {
        return true;
    } else {
        return false;
    }
}


function checkTeam($pin) {
    try {
        //read and parse hunt json
        $filename = '../hunt_sessions/' . $pin . '.json';
        $jsonString = file_get_contents($filename);
        $parsedJson = json_decode($jsonString, true);


        //check if team is left empty and should be deleted
        $teamList = $parsedJson["teams"];
        foreach ($teamlist as $team => $t) {
            if (count($team["players"]) == 0) {
                unset($parsedJson["teams"][$team]);
            }
        }

        //update json file
        $newJson = json_encode($parsedJson);
        file_put_contents($filename, $newJson);

        return;
    } catch (\Throwable $th) {
        return;
    }

}





/**
 * Check if the game in session still exists, and echo it back if it does
 *
 * @author Jakub Kwak
 */
function checkGame()
{
    session_start();
    
    if (isset($_SESSION['gameID']) && isset($_SESSION['nickname'])) {
        $gameID = $_SESSION['gameID'];
        $nickname = $_SESSION['nickname'];
        if (findGame($gameID)) {
            if (isset($_SESSION['teamName'])) {
                $teamName = $_SESSION['teamName'];
                echo json_encode(array("status" => "success", "gameID" => $gameID, "nickname" => $nickname, "teamName" => $teamName));
                return;
            } else {
                echo json_encode(array("status" => "success", "gameID" => $gameID, "nickname" => $nickname, "teamName" => null));
                return;
            }
        }
        unset($_SESSION['gameID']);
        unset($_SESSION['nickname']);
        unset($_SESSION['teamName']);
        echo json_encode(array("status" => "fail", "gameID" => null, "nickname" => null, "teamName" => null));
        return;
    }
    echo json_encode(array("status" => "fail", "gameID" => null, "nickname" => null, "teamName" => null));
}


checkGame();

?>