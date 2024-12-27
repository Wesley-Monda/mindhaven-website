<?php
$servername = "localhost";
$username = "wes3"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "mental_healthcare";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
