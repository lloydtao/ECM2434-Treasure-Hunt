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

$sql = "DROP DATABASE campustreks";
if ($conn->query($sql) === TRUE) {
    echo "Database dropped successfully <br>";
} else {
    echo "Error dropping database: " . $conn->error . "<br>";
}

$conn->close();
?>
