
<?php
session_start(); // Start the session

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Your MySQL root password
$dbname = "blood_donation"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for toast message
$toast_message = '';
$toast_type = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert the data into the database
    $sql = "INSERT INTO contact (full_name, email, message) VALUES ('$full_name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $toast_message = 'Message sent successfully!';
        $toast_type = 'success';
    } else {
        $toast_message = 'Error: ' . $conn->error;
        $toast_type = 'error';
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="contact.css?v=1.0">
    <style>
        /* Toast message styles */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 30px; /* Change from bottom to top */
            font-size: 17px;
        }

        .toast.success {
            background-color: #4CAF50;
        }

        .toast.error {
            background-color: #f44336;
        }

        .toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {top: 0; opacity: 0;} 
            to {top: 30px; opacity: 1;}
        }

        @keyframes fadein {
            from {top: 0; opacity: 0;}
            to {top: 30px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
            from {top: 30px; opacity: 1;} 
            to {top: 0; opacity: 0;}
        }

        @keyframes fadeout {
            from {top: 30px; opacity: 1;}
            to {top: 0; opacity: 0;}
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
    <div class="contact-container">
        <div class="contact-info">
            <div class="info-item">
                <img src="images/home.jpg" alt="Address Icon">
                <div>
                    <strong>Address</strong>
                    <p>4671 Sugar Camp Road,<br>Owatonna, Minnesota, 55060</p>
                </div>
            </div>
            <div class="info-item">
                <img src="images/phone.jpg" alt="Phone Icon">
                <div>
                    <strong>Phone</strong>
                    <p>9345678210</p>
                </div>
            </div>
            <div class="info-item">
                <img src="images/email.jpg" alt="Email Icon">
                <div>
                    <strong>Email</strong>
                    <p>Blood@email.com</p>
                </div>
            </div>
        </div>
        <div class="contact-form">
            <h2>Contact Us</h2>
            <p>Please enter all the information correctly to contact us..</p>
            <form action="" method="POST">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="message" placeholder="Type your Message..." required></textarea>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
    <?php include 'chatboat.html'; ?>

    <div class="footer">
        &copy; 2024 Blood Donation Website | All rights reserved.
    </div>

    <!-- Toast message -->
    <?php if (!empty($toast_message)): ?>
        <div id="toast" class="toast <?php echo $toast_type; ?>">
            <?php echo $toast_message; ?>
        </div>
        <script>
            // Show the toast message
            var toast = document.getElementById("toast");
            toast.className = "toast show <?php echo $toast_type; ?>";
            // Hide the toast after 3 seconds
            setTimeout(function(){ toast.className = toast.className.replace("show", ""); }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>
