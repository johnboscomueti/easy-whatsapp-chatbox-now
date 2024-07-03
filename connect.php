<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = ""; // Password for your MySQL server

// Create connection
$conn = new mysqli($servername, $username, $password, 'online_kenya');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
