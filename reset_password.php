<?php
include 'db_connect.php';

$token = $_GET['token'];
$new_password = $_POST['password'];
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

$sql = "SELECT email FROM password_resets WHERE token = '$token'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $email = $result->fetch_assoc()['email'];
    $update = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    $conn->query($update);
    echo "Password reset successful.";
} else {
    echo "Invalid or expired token.";
}

$conn->close();
?>
