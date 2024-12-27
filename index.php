<?php
// Database connection
$servername = "localhost";  // Use your database host (localhost or server IP)
$username = "wes3";         // Your database username
$password = "";             // Your database password (if any)
$dbname = "mindhaven";      // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables and initialize with empty values
$name = $email = $phone = $date = $time = $service = $message = "";
$name_err = $email_err = $phone_err = $date_err = $time_err = $service_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your full name.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    // Validate phone number
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } elseif (!preg_match("/^\+?[0-9]{10,15}$/", $_POST["phone"])) {
        $phone_err = "Please enter a valid phone number.";
    } else {
        $phone = htmlspecialchars(trim($_POST["phone"]));
    }

    // Validate date
    if (empty(trim($_POST["date"]))) {
        $date_err = "Please select a preferred date.";
    } else {
        $date = htmlspecialchars(trim($_POST["date"]));
    }

    // Validate time
    if (empty(trim($_POST["time"]))) {
        $time_err = "Please select a preferred time.";
    } else {
        $time = htmlspecialchars(trim($_POST["time"]));
    }

    // Validate service
    if (empty(trim($_POST["service"]))) {
        $service_err = "Please select a service.";
    } else {
        $service = htmlspecialchars(trim($_POST["service"]));
    }

    // Validate additional notes (optional)
    if (!empty(trim($_POST["message"]))) {
        $message = htmlspecialchars(trim($_POST["message"]));
    }

    // Check for errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($phone_err) && empty($date_err) && empty($time_err) && empty($service_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO bookings (name, email, phone, preferred_date, preferred_time, service, message) 
                VALUES ('$name', '$email', '$phone', '$date', '$time', '$service', '$message')";

        // Attempt to execute the prepared statement
        if ($conn->query($sql) === TRUE) {
            echo "<p>Thank you for booking an appointment with us. We will get back to you shortly.</p>";
        } else {
            echo "<p>Oops! Something went wrong. Please try again later.</p>";
        }
    }

    $email = $_POST['email'];

    // Check for duplicate email
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "This email is already registered.";
    } else {
        // Insert new booking
        $insert = $conn->prepare("INSERT INTO bookings (name, email, phone, preferred_date, preferred_time, service, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param(
            "sssssss",
            $_POST['name'],
            $email,
            $_POST['phone'],
            $_POST['date'],
            $_POST['time'],
            $_POST['service'],
            $_POST['message']
        );
    
        if ($insert->execute()) {
            echo "Booking successful!";
        } else {
            echo "Error: " . $insert->error;
        }
    }
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mindhaven Mentalcare - Book an Appointment</title>
    <link rel="stylesheet" href="styles.css">  <!-- Link to your CSS file -->
</head>
<body>

    <header>
        <h1>Welcome to Mindhaven Mentalcare</h1>
        <p>Hope, Heal, Thrive</p>
    </header>

    <main>
        <h2>Book an Appointment</h2>

        <!-- Display form validation errors -->
        <div class="error-messages">
            <?php
            if (!empty($name_err)) echo "<p class='error'>$name_err</p>";
            if (!empty($email_err)) echo "<p class='error'>$email_err</p>";
            if (!empty($phone_err)) echo "<p class='error'>$phone_err</p>";
            if (!empty($date_err)) echo "<p class='error'>$date_err</p>";
            if (!empty($time_err)) echo "<p class='error'>$time_err</p>";
            if (!empty($service_err)) echo "<p class='error'>$service_err</p>";
            ?>
        </div>

        <!-- Booking Form -->
        <form id="booking-form" method="post" action="index.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $phone; ?>" required pattern="^\+?[0-9]{10,15}$" title="Enter a valid phone number">
            </div>
            
            <div class="form-group">
                <label for="date">Preferred Date</label>
                <input type="date" id="date" name="date" value="<?php echo $date; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="time">Preferred Time</label>
                <select id="time" name="time" required>
                    <option value="08:00" <?php echo ($time == '08:00') ? 'selected' : ''; ?>>08:00 AM</option>
                    <option value="09:00" <?php echo ($time == '09:00') ? 'selected' : ''; ?>>09:00 AM</option>
                    <option value="10:00" <?php echo ($time == '10:00') ? 'selected' : ''; ?>>10:00 AM</option>
                    <option value="11:00" <?php echo ($time == '11:00') ? 'selected' : ''; ?>>11:00 AM</option>
                    <option value="12:00" <?php echo ($time == '12:00') ? 'selected' : ''; ?>>12:00 PM</option>
                    <option value="13:00" <?php echo ($time == '13:00') ? 'selected' : ''; ?>>01:00 PM</option>
                    <option value="14:00" <?php echo ($time == '14:00') ? 'selected' : ''; ?>>02:00 PM</option>
                    <option value="15:00" <?php echo ($time == '15:00') ? 'selected' : ''; ?>>03:00 PM</option>
                    <option value="16:00" <?php echo ($time == '16:00') ? 'selected' : ''; ?>>04:00 PM</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="service">Service Needed</label>
                <select id="service" name="service" required>
                    <option value="individual-therapy" <?php echo ($service == 'individual-therapy') ? 'selected' : ''; ?>>Individual Therapy</option>
                    <option value="group-therapy" <?php echo ($service == 'group-therapy') ? 'selected' : ''; ?>>Group Therapy</option>
                    <option value="family-therapy" <?php echo ($service == 'family-therapy') ? 'selected' : ''; ?>>Family Therapy</option>
                    <option value="medication-management" <?php echo ($service == 'medication-management') ? 'selected' : ''; ?>>Medication Management</option>
                    <option value="crisis-intervention" <?php echo ($service == 'crisis-intervention') ? 'selected' : ''; ?>>Crisis Intervention</option>
                    <option value="psychiatric-services" <?php echo ($service == 'psychiatric-services') ? 'selected' : ''; ?>>Psychiatric Services</option>
                    <option value="substance-abuse-treatment" <?php echo ($service == 'substance-abuse-treatment') ? 'selected' : ''; ?>>Substance Abuse Treatment</option>
                    <option value="cbt" <?php echo ($service == 'cbt') ? 'selected' : ''; ?>>Cognitive Behavioral Therapy (CBT)</option>
                    <option value="holistic-therapy" <?php echo ($service == 'holistic-therapy') ? 'selected' : ''; ?>>Holistic Therapy</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Additional Notes</label>
                <textarea id="message" name="message" rows="4"><?php echo $message; ?></textarea>
            </div>
            
            <button type="submit">Book Appointment</button>
        </form>
    </main>

</body>
</html>
