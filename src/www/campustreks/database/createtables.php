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

// sql to create table
$sql = "CREATE TABLE Users (
    Email VARCHAR(50) NOT NULL PRIMARY KEY,
    Username VARCHAR(30) NOT NULL,
    Password VARCHAR(30) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE Hunt (
    HuntID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(30) NOT NULL,
    Description TEXT NOT NULL,
    BestTeam VARCHAR(50),
    Highscore INT(6),
    Email VARCHAR(50) NOT NULL,
    FOREIGN KEY (Email) REFERENCES Users(Email),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE Objectives (
    ObjectiveID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    HuntID INT(6) NOT NULL,
    FOREIGN KEY (HuntID) REFERENCES Hunt(HuntID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE PhotoOps (
    ObjectiveID INT(6) NOT NULL,
    Specification TEXT NOT NULL,
    FOREIGN KEY (ObjectiveID) REFERENCES Objectives(ObjectiveID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";
    
$sql .= "CREATE TABLE Location (
    ObjectiveID INT(6) NOT NULL,
    HuntOrder INT(6) NOT NULL,
    Longitude INT(6) NOT NULL,
    Latitude INT(6) NOT NULL,
    Question VARCHAR(255) NOT NULL,
    Answer VARCHAR(50) NOT NULL,
    FOREIGN KEY (ObjectiveID) REFERENCES Objectives(ObjectiveID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->multi_query($sql) === TRUE) {
        echo "Tables created successfully <br>";
    } else {
        echo "Error creating tables: " . $conn->error . "<br>";
    }

$conn->close();
?>
