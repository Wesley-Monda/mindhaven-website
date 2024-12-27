<?php
include('db2.php');

$stmt = $conn->query("SELECT DATABASE()");
$database = $stmt->fetchColumn();
echo "Currently connected to the database: " . $database;
?>
