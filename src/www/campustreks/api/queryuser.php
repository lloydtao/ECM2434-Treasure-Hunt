<?php
include "../utils/connection.php";

$query = "SELECT * FROM Hunt";
$result = $conn->query($query);

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
        echo '<div class="col-md-6 col-lg-4">';
        echo '<div class="project-card-no-image">';
        echo '<h3>' . $row["Name"] . '</h3>';
        echo '<h4>Author: ' . $row["Username"];
        if ($verified == 1) {
            echo ' <img src="img/exeter-logo.png" height="14px" width="14px"></h4>';
        } else {
            echo '</h4>';
        }
        echo '<h4>' . $row["Description"] . '</h4>';
        echo '<a class="btn btn-outline-primary btn-sm" role="button" href="#" onclick=startHunt(' . $row["HuntID"] . ')>Host</a>';
        echo '<div class="tags">High Score: ' . $row["Highscore"] . '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo 'No hunts found. Click <a href="/create.php">here</a> to create a new hunt.<br>';
}
?>