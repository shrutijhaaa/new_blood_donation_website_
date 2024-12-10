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

$toast_message = '';
$toast_type = '';
$redirect_url = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);

    if ($stmt = $conn->prepare("SELECT otp, otp_expiry FROM signup WHERE Email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_otp = $row['otp'];
            $otp_expiry = $row['otp_expiry'];

            if ($otp === $stored_otp && new DateTime() <= new DateTime($otp_expiry)) {
                $_SESSION['email'] = $email; // Set the session email
                $toast_message = "OTP verification successful!";
                $toast_type = "success";
                $redirect_url = "reset_password.php"; // Redirect to password reset page

                // Optional: Update the database to reset OTP
                if ($update_stmt = $conn->prepare("UPDATE signup SET otp = NULL, otp_expiry = NULL WHERE Email = ?")) {
                    $update_stmt->bind_param("s", $email);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            } else {
                $toast_message = "Invalid or expired OTP!";
                $toast_type = "error";
            }
        } else {
            $toast_message = "Email not found!";
            $toast_type = "error";
        }

        $stmt->close();
    } else {
        $toast_message = "Error preparing statement: " . $conn->error;
        $toast_type = "error";
    }

    $conn->close();

    $_SESSION['toast_message'] = $toast_message;
    $_SESSION['toast_type'] = $toast_type;

    header("Location: " . ($redirect_url ?: 'verify_otp.php'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="verify_otp.css?v=1.0">
    <title>Verify OTP</title>
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
        <h2>Verify OTP</h2>
        <form action="verify_otp.php" method="POST">
            <label for="email">Enter your email:</label>
            <input type="email" name="email" required>
            
            <label for="otp">Enter the OTP:</label>
            <input type="text" name="otp" required>
            
            <button type="submit">Verify OTP</button>
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

        if (toastMessage !== '') {
            var toast = document.getElementById("toast");
            toast.className = 'toast ' + toastType;
            toast.innerHTML = toastMessage;
            toast.classList.add("show");

            // Hide toast after 5 seconds
            setTimeout(function() {
                toast.classList.remove("show");
            }, 5000); // 5 seconds
        }

        // Clear session messages after showing
        <?php unset($_SESSION['toast_message'], $_SESSION['toast_type']); ?>
    };
</script>

<div class="footer">
    &copy; 2024 Blood Donation Website | All rights reserved.
</div>
</body>
</html>
