<?php
$host = "localhost";
$username = "root";
$password = "";
$db = "phpadminpanel";

// Create connection
$conn = new mysqli($host, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>