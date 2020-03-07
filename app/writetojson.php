<?php

function writeToJson($game, $team, $id){
    $inp = file_get_contents('hunt_sessions/$game.json');
    $tempArray = json_decode($inp);

    foreach($tempArray["teams"]["$team"]["objectives"] as $objective){
        if($objective["objectiveId"] == $id){
            $objective["completed"] = "true";
        }
    }

    array_push($tempArray, $data);
    $jsonData = json_encode($tempArray);
    file_put_contents('hunt_sessions/$game.json', $jsonData);
}
      
if($_SERVER["REQUEST_METHOD"] == "POST"){
    writeToJson($_POST["Game"], $_POST["Team"], $_POST["Id"])
}