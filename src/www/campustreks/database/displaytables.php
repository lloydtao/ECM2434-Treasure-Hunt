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

// Gets all tables and outputs each row from each table
$sql = "SELECT * FROM Users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Users <br>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Email: " . $row["Email"]. " - Username: " . $row["Username"]. " - Password: " . $row["Password"] . "<br>";
    }
} else {
    echo "0 results <br>";
}

$sql = "SELECT * FROM Hunt";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Hunt <br>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "HuntID: " . $row["HuntID"]. " - Name: " . $row["Name"]. 
        " - Description: " . $row["Description"] ." - BestTeam: " . $row["BestTeam"] .
        " - Highscore: " . $row["Highscore"] ." - Email: " . $row["Email"] . "<br>";
    }
} else {
    echo "0 results <br>";
}

$sql = "SELECT * FROM Objectives";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Objectives <br>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "ObjectiveID: " . $row["ObjectiveID"]. " - HuntID: " . $row["HuntID"]. "<br>";
    }
} else {
    echo "0 results <br>";
}

$sql = "SELECT * FROM PhotoOps";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "PhotoOps <br>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "ObjectiveID: " . $row["ObjectiveID"]. " - Specification: " . $row["Specification"] . "<br>";
    }
} else {
    echo "0 results <br>";
}

$sql = "SELECT * FROM Location";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Location <br>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "ObjectiveID: " . $row["ObjectiveID"]. " - HuntOrder: " . $row["HuntOrder"]. 
        " - Longitude: " . $row["Longitude"] ." - Latitude: " . $row["Latitude"] 
        ." - Question: " . $row["Question"] . " - Answer: " . $row["Answer"] ."<br>";
    }
} else {
    echo "0 results <br>";
}

$conn->close();
?>
