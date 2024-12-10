<?php
session_start();

// Database connection settings
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blood_donation'; // Ensure this matches your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['email'])) {
        die("Session email not set.");
    }

    $email = trim($_SESSION['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password
    if (!preg_match('/^[a-zA-Z0-9]+$/', $new_password)) {
        $message = "Password must be alphanumeric only!";
        $message_type = "error";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
        $message_type = "error";
    } else {
        // No hashing, store plain text password
        if ($stmt = $conn->prepare("UPDATE signup SET Password = ? WHERE Email = ?")) {
            $stmt->bind_param("ss", $new_password, $email);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = "Password reset successful!";
                    $message_type = "success";

                    // Clear OTP
                    $update_stmt = $conn->prepare("UPDATE signup SET otp = NULL, otp_expiry = NULL WHERE Email = ?");
                    $update_stmt->bind_param("s", $email);
                    $update_stmt->execute();
                    $update_stmt->close();

                    unset($_SESSION['email']);
                    header("Location: login.php");
                    exit();
                } else {
                    $message = "No record found to update. Please check your email.";
                    $message_type = "error";
                }
            } else {
                $message = "Error updating password: " . $stmt->error;
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "Error preparing update statement: " . $conn->error;
            $message_type = "error";
        }
    }

    $conn->close();

    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $message_type;

    header("Location: reset_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset_password.css?v=1.0">
    <title>Reset Password</title>
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
    <div class="reset_password">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit">Reset Password</button>
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
        var message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
        var messageType = "<?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : ''; ?>";

        if (message !== '') {
            var toast = document.getElementById("toast");
            toast.className = 'toast ' + messageType;
            toast.innerHTML = message;
            toast.classList.add("show");

            // Hide toast after 5 seconds
            setTimeout(function() {
                toast.classList.remove("show");
            }, 5000); // 5 seconds
        }

        // Clear session messages after showing
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    };
</script>

<div class="footer">
    &copy; 2024 Blood Donation Website | All rights reserved.
</div>
</body>
</html>
