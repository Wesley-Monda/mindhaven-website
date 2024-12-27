<?php
// Include your database connection file
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'register':
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $full_name, $email, $password);
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $stmt->error;
            }
            break;

        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $hashed_password);
            if ($stmt->fetch() && password_verify($password, $hashed_password)) {
                echo "Login successful!";
            } else {
                echo "Invalid email or password.";
            }
            break;

        case 'forgot_password':
            $email = $_POST['email'];
            $token = bin2hex(random_bytes(50));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $conn->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $token, $expires_at);
            if ($stmt->execute()) {
                // Send email with reset link (pseudo code)
                // mail($email, "Password Reset", "Reset your password using this link: https://yourwebsite.com/reset_password.php?token=$token");
                echo "Reset link sent to your email.";
            } else {
                echo "Error: " . $stmt->error;
            }
            break;

        case 'reset_password':
            $token = $_POST['token'];
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("SELECT email FROM password_reset_tokens WHERE token = ? AND expires_at > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($email);
            if ($stmt->fetch()) {
                $stmt->close();

                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $new_password, $email);
                if ($stmt->execute()) {
                    echo "Password reset successful!";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Invalid or expired token.";
            }
            break;

        case 'book_appointment':
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $message = $_POST['message'];
            $user_id = 1; // Assuming the user is logged in and user_id is 1 for demonstration

            $stmt = $conn->prepare("INSERT INTO appointments (user_id, full_name, email, phone, date, time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $user_id, $full_name, $email, $phone, $date, $time, $message);
            if ($stmt->execute()) {
                echo "Appointment booked successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            break;
    }
}
?>
