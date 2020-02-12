<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campustreks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "<br>");
}

$sql = "INSERT INTO Users (Email, Username, PasswordHash, PasswordSalt)
VALUES ('John@example.com', 'JohnDoe', 'safepassword', 'salt');";
$sql .= "INSERT INTO Users (Email, Username, PasswordHash, PasswordSalt)
VALUES ('Tom@email.co.uk', 'TomTom', 'secretword', 'pepper');";
$sql .= "INSERT INTO Hunt (Name, Description, BestTeam, Highscore, Email)
VALUES ('Forum Finder', 'There are many routes to the Forum and along the way many interesting buildings.', 'Team10', '4000', 'John@example.com');";
$sql .= "INSERT INTO Hunt (Name, Description, BestTeam, Highscore, Email)
VALUES ('Innovation Hunt', 'A trip to both the innovations whilst visiting various useful locations.', 'TrailBlazers', '6280', 'Tom@email.co.uk');";
$sql .= "INSERT INTO Objectives (HuntID)
VALUES ('1');";
$sql .= "INSERT INTO PhotoOps (ObjectiveID, Specification)
VALUES ('1', 'Take a picture of the tallest building you can see.');";
$sql .= "INSERT INTO Location (ObjectiveID, HuntOrder, Longitude, Latitude, Question, Answer)
VALUES ('1', '1', '400', '234', 'What are the colours that some rooms are named after inside of Peter Chalk?', 'Blue, Green, Purple and Red')";

if ($conn->multi_query($sql) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>