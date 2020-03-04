<?php
    include "utils/connection.php";
    

    function getNextLoc($conn, $id){
        $sql = "FROM Location SELECT Longitude, Latitude 
        WHERE ObjectiveID = '{$id}';"
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        getNextLoc(opencon(), $_POST["ID"]);
    }

