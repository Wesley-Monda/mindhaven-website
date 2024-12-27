<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$database = "MindhavenMentalcare";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Register user
if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $full_name, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Login user
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if ($user_id && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        echo "Login successful!";
    } else {
        echo "Invalid email or password.";
    }
    $stmt->close();
}

// Book appointment
if (isset($_POST['book'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to book.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $booking_date = $_POST['date'];
    $booking_time = $_POST['time'];
    $phone = $_POST['phone'];
    $notes = $_POST['message'];

    // Generate unique booking ID and code
    $booking_id = "BKG" . strtoupper(uniqid());
    $unique_code = strtoupper(substr(md5(mt_rand()), 0, 8));

    $sql = "INSERT INTO bookings (user_id, booking_date, booking_time, phone, notes, booking_id, unique_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $booking_date, $booking_time, $phone, $notes, $booking_id, $unique_code);

    if ($stmt->execute()) {
        echo json_encode([
            "message" => "Booking successful!",
            "booking_id" => $booking_id,
            "unique_code" => $unique_code
        ]);
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
