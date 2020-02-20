<?php
include "../utils/connection.php";

// Check connection
$conn = openCon();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "<br>");
}

$sql = "INSERT INTO Users (Email, Username, Password)
VALUES ('John@example.com', 'JohnDoe', 'safepassword');";
$sql .= "INSERT INTO Users (Email, Username, Password)
VALUES ('Tom@email.co.uk', 'TomTom', 'secretword');";
$sql .= "INSERT INTO Hunt (Name, Description, BestTeam, Highscore, Email)
VALUES ('Forum Finder', 'There are many routes to the Forum and along the way many interesting buildings.', 'Team10', '4000', 'John@example.com');";
$sql .= "INSERT INTO Hunt (Name, Description, BestTeam, Highscore, Email)
VALUES ('Innovation Hunt', 'A trip to both the innovations whilst visiting various useful locations.', 'TrailBlazers', '6280', 'Tom@email.co.uk');";
$sql .= "INSERT INTO Objectives (HuntID)
VALUES ('1');";
$sql .= "INSERT INTO PhotoOps (ObjectiveID, Specification)
VALUES ('1', 'Take a picture of the tallest building you can see.');";
$sql .= "INSERT INTO Location (ObjectiveID, HuntOrder, Longitude, Latitude, Question, Answer)
VALUES ('1', '1', '400', '234', 'What are the colours that some rooms are named after inside of Peter Chalk?', 'Blue, Green, Purple and Red');";
$sql .= "INSERT INTO HuntData (HuntID)
VALUES ('1');";

if ($conn->multi_query($sql) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>
