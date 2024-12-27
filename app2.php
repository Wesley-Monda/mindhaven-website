<?php
session_start(); // Start a session to store user data

// Include your database connection file
include('db2.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'register':
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $security_question = $_POST['security_question'];
            $security_answer = password_hash($_POST['security_answer'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $full_name);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $password);
            $stmt->bindParam(4, $security_question);
            $stmt->bindParam(5, $security_answer);
            if ($stmt->execute()) {
                // Retrieve the user ID of the newly registered user
                $user_id = $conn->lastInsertId();
                $_SESSION['user_id'] = $user_id; // Store the user ID in the session
                echo "register_success";
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
            break;

        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $stmt->bindColumn(1, $id);
            $stmt->bindColumn(2, $hashed_password);
            if ($stmt->fetch() && password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id; // Store the user ID in the session
                echo "login_success";
            } else {
                echo "Invalid email or password.";
            }
            break;

        case 'forgot_password':
            $email = $_POST['email'];
            $token = bin2hex(random_bytes(50));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $conn->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $token);
            $stmt->bindParam(3, $expires_at);
            if ($stmt->execute()) {
                // Send email with reset link using PHPMailer
                // (The actual PHPMailer code should go here)
                echo 'Reset link sent to your email.';
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
            break;

        case 'reset_password_with_security_answer':
            $email = $_POST['email'];
            $security_answer = $_POST['security_answer'];
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("SELECT security_answer FROM users WHERE email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $stmt->bindColumn(1, $stored_answer);
            if ($stmt->fetch() && password_verify($security_answer, $stored_answer)) {
                $stmt->closeCursor(); // Close the cursor to allow the next query
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bindParam(1, $new_password);
                $stmt->bindParam(2, $email);
                if ($stmt->execute()) {
                    echo "Password reset successful!";
                } else {
                    echo "Error: " . $stmt->errorInfo()[2];
                }
            } else {
                echo "Invalid security answer.";
            }
            break;

        case 'book_appointment':
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $message = $_POST['message'];

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id']; // Get the user ID from the session

                $stmt = $conn->prepare("INSERT INTO appointments (user_id, full_name, email, phone, date, time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bindParam(1, $user_id);
                $stmt->bindParam(2, $full_name);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $phone);
                $stmt->bindParam(5, $date);
                $stmt->bindParam(6, $time);
                $stmt->bindParam(7, $message);
                if ($stmt->execute()) {
                    echo "Appointment booked successfully!";
                } else {
                    echo "Error: " . $stmt->errorInfo()[2];
                }
            } else {
                echo "User not logged in.";
            }
            break;
    }
}
?>
