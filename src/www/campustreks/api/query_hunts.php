<?php
/**
 *
 */

include "../utils/connection.php";

$conn = openCon();

$query = "SELECT * FROM Hunt";
$result = $conn->query($query);
$json = array(
    "status" => "fail",
    "results" => array()
);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //check if user is verified
        $verified = 0;
        $huntUser = $row["Username"];
        $queryUser = "SELECT `Verified` FROM `users` WHERE `Username` = '$huntUser'";
        $resultUser = $conn->query($queryUser);
        if ($resultUser->num_rows > 0) {
            $rowUser = $resultUser->fetch_assoc();
            $verified = $rowUser["Verified"];
        }

        $json["results"][] = array("huntid" => $row["HuntID"], "name" => $row["Name"], "username" => $row["Username"], 
                            "description" => $row["Description"], "highscore" => $row["Highscore"], 
                            "bestteam" => $row["BestTeam"], "verified" => $verified);
    }
}

$json["status"] = "success";
echo json_encode($json);
return;
?>