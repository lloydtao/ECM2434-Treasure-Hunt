<meta name="author" content = "James Caddock">
<meta name="Contributer" content = "Joe lintern">
<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "<br>");
}

// Drops database
$sql = "DROP DATABASE IF EXISTS campustreks";
if ($conn->query($sql) === TRUE) {
    echo "Database dropped successfully <br>";
} else {
    echo "Error dropping database: " . $conn->error . "<br>";
}


// Create database
$sql = "CREATE DATABASE campustreks";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully <br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}


// sql to create table
$sql = "USE campustreks;
    CREATE TABLE Users (
    Email VARCHAR(50) NOT NULL PRIMARY KEY,
    Username VARCHAR(30) UNIQUE NOT NULL,
    Password VARCHAR(70) NOT NULL,
    Verified BOOLEAN NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE Hunt (
    HuntID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(30) NOT NULL,
    Description TEXT NOT NULL,
    BestTeam VARCHAR(50),
    Highscore INT(6),
    Username VARCHAR(30) NOT NULL,
    FOREIGN KEY (Username) REFERENCES Users(Username),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE Objectives (
    ObjectiveID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    HuntID INT(6) UNSIGNED NOT NULL,
    FOREIGN KEY (HuntID) REFERENCES Hunt(HuntID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE PhotoOps (
    ObjectiveID INT(6) UNSIGNED NOT NULL,
    Specification TEXT NOT NULL,
    FOREIGN KEY (ObjectiveID) REFERENCES Objectives(ObjectiveID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE Location (
    ObjectiveID INT(6) UNSIGNED NOT NULL,
    HuntOrder INT(6) NOT NULL,
    Longitude INT(6) NOT NULL,
    Latitude INT(6) NOT NULL,
    Question VARCHAR(255) NOT NULL,
    Answer VARCHAR(50) NOT NULL,
    Direction VARCHAR(255) NOT NULL,
    FOREIGN KEY (ObjectiveID) REFERENCES Objectives(ObjectiveID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

$sql .= "CREATE TABLE HuntData (
    JsonID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    HuntID INT(6) UNSIGNED NOT NULL,
    FOREIGN KEY (HuntID) REFERENCES Hunt(HuntID),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    json JSON NOT NULL);";

    if ($conn->multi_query($sql) === TRUE) {
        echo "Tables created successfully <br>";
    } else {
        echo "Error creating tables: " . $conn->error . "<br>";
    }

$conn->close();
?>
