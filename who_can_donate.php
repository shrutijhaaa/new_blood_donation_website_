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
    <title>Who Can Donate Blood?</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="who_can_donate.css">
</head>
<body>
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
                        <a href="logout.php" class="fas fa-user-alt"></a>
                    <?php else: ?>
                        <a href="login.php" class="">Login</a>
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
    <section class="requirements">
        <ul>
            <li>Weight at least 110 lbs (50 kg)</li>
            <li>Pulse rate: Between 60 and 100 beats/minute with regular rhythm</li>
            <li>Blood pressure: Between 90 and 160 systolic and 60 and 100 diastolic</li>
            <li>Hemoglobin: At least 125 g/L</li>
            <li>No alcohol intake for 24 hr before donating</li>
            <li>No minor/major surgery less than a year (12 months)</li>
            <li>No tattoo/piercing less than a year (12 months)</li>
            <li>Must have 6-8 hours of sleep</li>
        </ul>
    <p>If you are eligible</p>    
    <div class="button">
        <button type="button"onclick="window.location.href='donate.php';">DONATE</button>
    </div>
    </section>
    <?php include 'chatboat.html'; ?>
</div> <div class="footer">
    &copy; 2024 Blood Donation Website | All rights reserved.
</div>
</body>
</html>
