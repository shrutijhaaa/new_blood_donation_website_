<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="signup.css">
    <style>
        .toast {
            display: none;
            position: fixed;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #66ff66; /* Default to success color */
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
                if (type !== 'error') {
                    window.location.href = 'login.php'; // Redirect to login page on success
                }
            }, 2500);
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
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="avilable_blood.php"><i class="fas fa-envelope"></i> Donor</a></li>
                <li><a href="contact.php"><i class="fas fa-user"></i>Contact</a></li>
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
    <div class="wrapper">
        <div class="info">
            <h2>INFORMATION</h2>
            <p>Thank you for visiting our platform. Your willingness to donate blood is a generous act that can save lives. Here, you can easily register as a donor, manage your donation schedule, and stay informed about upcoming blood donation drives.</p>
            <p><strong> We are committed </strong> to making the donation process as smooth and efficient as possible.</p>
            <button type="button" onclick="window.location.href='login.php';">Have An Account</button>
        </div>
        <div class="form-container">
            <h2>REGISTER FORM</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="action" value="signup">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Blood Donation Website | All rights reserved.
    </div>

    <?php
    // Database connection
    require_once 'db_config.php';

    $message = '';
    $message_type = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Check if passwords match
        if ($password !== $confirm_password) {
            $message = 'Passwords do not match.';
            $message_type = 'error';
        } else {
            // Check if the email already exists
            $sql_check = "SELECT * FROM signup WHERE email = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $message = 'Email already registered.';
                $message_type = 'error';
            } else {
                // Prepare and execute the SQL query
                $sql = "INSERT INTO signup (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

                if ($stmt->execute()) {
                    $message = 'Registration occur successful! please Login now';
                    $message_type = 'success';
                } else {
                    $message = 'Could not register.Please register first!';
                    $message_type = 'error';
                }
                $stmt->close();
            }
            $stmt_check->close();
        }
        $conn->close();

        if ($message) {
            echo "<script>showToast('$message', '$message_type');</script>";
        }
    }
    ?>
</body>
</html>
