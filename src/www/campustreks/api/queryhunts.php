<?php
include "../utils/connection.php";

$query = "SELECT * FROM Hunt";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $json->status = "success";
        $json->name = $row["Name"];
        $json->username = $row["Username"];
        $json->description = $row["Description"];
        $json->huntID = $row["HuntID"];
        $json->highscore = $row["Highscore"];

        //check if user is verified
        $verified = 0;
        $huntUser = $row["Username"];
        $queryUser = "SELECT `Verified` FROM `users` WHERE `Username` = '$huntUser'";
        $resultUser = $conn->query($queryUser);
        if ($resultUser->num_rows > 0) {
            $rowUser = $resultUser->fetch_assoc();
            $json->verified = $rowUser["Verified"];
        }
    }
} else {
    echo json_encode(array("status" => "fail"));
    return;
}

echo json_encode($json);
return;
?>