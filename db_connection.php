<?php
// Database connection details
$host = "localhost";
$username = "wes3";  // Your MySQL username
$password = "";      // Your MySQL password
$dbname = "mindhaven_system";  // The correct database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connection successful!";  // Optional for debugging
}
?>
