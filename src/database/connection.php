<?php
function openCon()
{
    // Create connection
    $conn = new mysqli("localhost", "root", "", "campustreks");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
