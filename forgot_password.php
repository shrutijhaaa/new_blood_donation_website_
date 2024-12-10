<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Start session to handle toast messages

// Load Composer's autoloader
require 'vendor/autoload.php';

// Database connection settings
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blood_donation';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize toast message variables
$toast_message = '';
$toast_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Prepare and execute statement to avoid SQL injection
    if ($stmt = $conn->prepare("SELECT * FROM signup WHERE email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate OTP
            $otp = rand(100000, 999999); // Example OTP
            $otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            // Prepare and execute statement to update OTP
            if ($update_stmt = $conn->prepare("UPDATE signup SET otp = ?, otp_expiry = ? WHERE email = ?")) {
                $update_stmt->bind_param("sss", $otp, $otp_expiry, $email);
                $update_stmt->execute();
                $update_stmt->close();

                // Create a new PHPMailer object
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
                    $mail->setFrom('jhashruti932@gmail.com', 'Shruti Jha');
                    $mail->addAddress($email); // Recipient email

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body    = "Your OTP code is $otp. It is valid for 10 minutes.";

                    $mail->send();
                    
                    // Set success message and OTP sent flag
                    $_SESSION['toast_message'] = 'OTP has been sent to your email!';
                    $_SESSION['toast_type'] = 'success';
                    $_SESSION['otp_sent'] = 'true'; // OTP sent flag

                } catch (Exception $e) {
                    $_SESSION['toast_message'] = "Message could not be sent. Error: {$mail->ErrorInfo}";
                    $_SESSION['toast_type'] = 'error';
                }
            } else {
                $_SESSION['toast_message'] = "Error updating OTP in database! " . $conn->error; // Debugging SQL error
                $_SESSION['toast_type'] = 'error';
            }
        } else {
            $_SESSION['toast_message'] = "Email not found!";
            $_SESSION['toast_type'] = 'error';
        }

        $stmt->close();
    } else {
        // Display exact SQL error
        $_SESSION['toast_message'] = "Error preparing statement: " . $conn->error;
        $_SESSION['toast_type'] = 'error';
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forget_password.css?v=1.0">
    <title>Forget Password</title>
    <style>
        /* Toast message styling */
        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 30px;
            transform: translateX(-50%);
            font-size: 17px;
        }

        .toast.success { background-color: #4CAF50; }
        .toast.error { background-color: #f44336; }

        .toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 4.5s; /* Adjust fadeout to 4.5s for a total of 5s */
        }

        @keyframes fadein {
            from { top: 0; opacity: 0; }
            to { top: 30px; opacity: 1; }
        }

        @keyframes fadeout {
            from { top: 30px; opacity: 1; }
            to { top: 0; opacity: 0; }
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="images/logo1.jpg" alt="Logo">
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="avilable_blood.php"><i class="fas fa-envelope"></i> Donor</a></li>
            <li><a href="contact.php"><i class="fas fa-user"></i> Contact</a></li>
            <li><a href="login.php"><i class="fas fa-user"></i> Login</a></li>
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
    <div class="forget_password">
        <h2>Forget Password</h2>
        <form action="" method="POST">
            Enter your Email <input type="email" name="email" required>
            <button type="submit">Get OTP</button>
        </form>
    </div>
    <div class="welcome-container">
        <h2>Welcome back!</h2>
        <p>We hope you had a safe and enjoyable time away.</p>
    </div>
</div>

<!-- Toast message -->
<div id="toast" class="toast"></div>

<script>
    // Display toast message
    window.onload = function() {
        var toastMessage = "<?php echo isset($_SESSION['toast_message']) ? $_SESSION['toast_message'] : ''; ?>";
        var toastType = "<?php echo isset($_SESSION['toast_type']) ? $_SESSION['toast_type'] : ''; ?>";
        var isOtpSent = "<?php echo isset($_SESSION['otp_sent']) ? $_SESSION['otp_sent'] : ''; ?>"; // OTP sent flag

        if (toastMessage !== '') {
            var toast = document.getElementById("toast");
            toast.className = 'toast ' + toastType;
            toast.innerHTML = toastMessage;
            toast.classList.add("show");

            // Hide toast after 5 seconds
            setTimeout(function() {
                toast.classList.remove("show");
            }, 5000); // 5 seconds

            // If OTP was successfully sent, redirect to OTP verification page
            if (isOtpSent === 'true') {
                setTimeout(function() {
                    window.location.href = 'verify_otp.php'; // Redirect to OTP verification page
                }, 5000); // Redirect after 5 seconds
            }
        }

        // Clear session messages after showing
        <?php unset($_SESSION['toast_message'], $_SESSION['toast_type'], $_SESSION['otp_sent']); ?>
    };
</script>

<div class="footer">
    &copy; 2024 Blood Donation Website | All rights reserved.
</div>
</body>
</html>
