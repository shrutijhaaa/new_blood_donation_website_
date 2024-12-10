<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="blood_booking.css?v=1.0">
    <style>
        .toast {
            display: none;
            position: fixed;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50; /* Default to success color */
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
        }
        .toast.error {
            background-color: #d83545; /* Error color */
        }
    </style>
    <script>
        function showToast(message, type) {
            var toast = document.getElementById("toast");

            toast.textContent = message;
            toast.className = 'toast'; // Reset class name
            if (type === 'error') {
                toast.classList.add('error'); // Add error class
            } else {
                toast.classList.remove('error'); // Remove error class
            }

            toast.style.display = "flex";
            setTimeout(function() {
                toast.style.display = "none";
            }, 5000);
        }
    </script>
</head>
<body>
    <div id="toast" class="toast"></div>
    <header>
        <div class="logo">
            <img src="images/logo1.jpg" alt="Logo">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php"><i class=""></i> Home</a></li>
                <li><a href="about.php"><i class=""></i> About</a></li>
                <li><a href="avilable_blood.php"><i class=""></i> Donor</a></li>
                <li><a href="contact.php"><i class=""></i> Contact</a></li>
                <!-- User icon or login/logout link based on session status -->
                <li>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <!-- Display user's initial in a styled div -->
                        <a href="logout.php"class="fas fa-user-alt"></a>
                    <?php else: ?>
                        <a href="login.php" class="">Login</i></a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </header>
    <div class="video-container">
        <div class="video-wrapper">
            <video autoplay muted loop id="bg-video">
                <source src="images/blood-vedio.mp4" type="video/mp4">
                Your browser does not support HTML5 video.
            </video>
        </div>
    </div>
    <div class="container">
        <h1>Blood Booking</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="Name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="Email" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone_number" name="Phone_Number" required>

            <label for="blood-group">Blood Group</label>
            <select id="blood_group" name="Blood_Group" required>
                <option value="">Select your blood group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>

            <label for="state">State</label>
            <select id="state" name="State" required>
                <option value="">Select State</option>
                <option value="state1">Andhra Pradesh</option>
                <option value="state2">Arunachal Pradesh</option>
                <option value="state1">Bihar</option>
                <option value="state2">Chhattisgarh</option>
                <option value="state1">Goa</option>
                <option value="state2">Gujarat</option>
                <option value="state1">Haryana</option>
                <option value="state2">Himachal Pradesh</option>
                <option value="state1">Madhya Pradesh</option>
                <option value="state2">Maharashtra</option>
                <option value="state1">Rajasthan</option>
                <option value="state2">Uttar Pradesh</option>
                <option value="state1">Delhi</option>
                <option value="state2">Tamil Nadu</option>
            </select>

            <label for="date">Preferred Donation Date</label>
            <input type="date" id="date" name="Date" required>

            <label for="time">Preferred Donation Time</label>
            <input type="time" id="time" name="Time" required>

            <button type="submit">Book blood</button>
        </form>
    </div>
  
    <div class="footer">
        &copy; 2024 Blood Donation Website | All rights reserved.
    </div>

    <?php
    // Database connection
    require_once 'db_config.php';

    // Include PHPMailer classes
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    require 'phpmailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $message = '';
    $message_type = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $name = $_POST['Name'] ?? '';
        $email = $_POST['Email'] ?? '';
        $phone_number = $_POST['Phone_Number'] ?? '';
        $blood_group = $_POST['Blood_Group'] ?? '';
        $state = $_POST['State'] ?? '';
        $date = $_POST['Date'] ?? '';
        $time = $_POST['Time'] ?? '';

        // Check if all required fields are filled
        if (empty($name) || empty($email) || empty($phone_number) || empty($blood_group) || empty($state) || empty($date) || empty($time)) {
            $message = 'Error: All fields are required.';
            $message_type = 'error';
        } else {
            // Check if the email or phone number already exists
            $sql_check = "SELECT * FROM book_blood WHERE Email = ? OR Phone_Number = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ss", $email, $phone_number);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // Check for duplicate email or phone number
                $duplicate = false;
                while ($row = $result_check->fetch_assoc()) {
                    if ($row['Email'] === $email || $row['Phone_Number'] === $phone_number) {
                        $duplicate = true;
                        break;
                    }
                }
                if ($duplicate) {
                    $message = 'Error: A booking of the same user already exists.';
                    $message_type = 'error';
                }
            } else {
                // Prepare and execute the SQL query
                $sql = "INSERT INTO book_blood (Name, Email, Phone_Number, Blood_Group, State, Date, Time) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $name, $email, $phone_number, $blood_group, $state, $date, $time);

                try {
                    if ($stmt->execute()) {
                        $message = "You have successfully registered.";
                        $message_type = 'success';

                        // PHPMailer setup
                        $mail = new PHPMailer(true);
                        try {
                            // Server settings
                            $mail->isSMTP();
                            $mail->Host       = 'smtp.gmail.com';  // Gmail SMTP server
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'jhashruti932@gmail.com';  // Your Gmail address
                            $mail->Password   = 'rrbe dzkz qxrv luwh';  // Your Gmail account password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 587;

                            // Recipients
                            $mail->setFrom('jhashruti932@gmail.com', 'Blood Donation');
                            $mail->addAddress($email, $name);

                            // Content
                            $mail->isHTML(true);
                            $mail->Subject = 'Blood Booking Confirmation';
                            $mail->Body    = "Thank you, $name! Your blood booking is done for $date at $time. Further details will be provided very soon.";

                            $mail->send();
                            $message .= " Email notification has been sent.";
                        } catch (Exception $e) {
                            $message .= " Email notification failed: " . $mail->ErrorInfo;
                            $message_type = 'error';
                        }
                    } else {
                        $message = "Error: There was an issue with your booking. Please try again.";
                        $message_type = 'error';
                    }
                } catch (Exception $e) {
                    $message = "Error: " . $e->getMessage();
                    $message_type = 'error';
                }
            }
        }
        // Show the toast message
        echo "<script>showToast('$message', '$message_type');</script>";
    }
    ?>
    <?php include 'chatboat.html'; ?>
</body>
</html>
