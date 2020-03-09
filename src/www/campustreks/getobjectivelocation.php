<?php
include "utils/connection.php";        

function getNextLoc($conn, $id){
    $sql = "SELECT Longitude, Latitude FROM Location 
    WHERE ObjectiveID = '{$id}';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();
    echo json_encode(array("coords"=>array("latitude"=>$row["Latitude"], "longitude"=>$row["Longitude"])));
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    getNextLoc(opencon(), $_GET["ID"]);
}

?>