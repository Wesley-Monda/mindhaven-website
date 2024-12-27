<?php
$servername = "localhost";
$username = "wes3";
$password = ""; // No password
$dbname = "mindhaven";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
