<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css?v=1.0">
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
                    window.location.href = 'index.html'; // Redirect to index page on success
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
        <div class="signin-container">
            <h2>Login</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            Enter Username <input type="email" name="email" required>
            Enter Password <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
             <p>No account yet? <a href="signup.php">Signup</a></p>
            <p>Forgot your password? <a href="forgot_password.php">Click here</a></p>

        </div>
        <div class="welcome-container">
            <h2>Welcome back!</h2>
            <p>We hope you had a safe and enjoyable time away.</p>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Blood Donation Website | All rights reserved.
    </div>

    <?php
session_start(); // Start the session

require_once 'db_config.php';

$message = '';
$message_type = '';

// Define admin credentials
$admin_email = 'admin@gmail.com'; // Replace with your admin email
$admin_password = 'admin';       // Replace with your admin password

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Check if the login attempt is for the admin
    if ($email === strtolower($admin_email) && $password === $admin_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_type'] = 'admin'; // Set session for admin
        header('Location: admin.php'); // Redirect to the admin page
        exit();
    }

    // Prepare and execute query to fetch user by email
    $sql_check = "SELECT * FROM signup WHERE LOWER(email) = LOWER(?)";
    $stmt_check = $conn->prepare($sql_check);

    if (!$stmt_check) {
        die('Error preparing statement: ' . $conn->error);
    }

    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // Fetch user data
    if ($result_check && $result_check->num_rows > 0) {
        $user = $result_check->fetch_assoc();

        if (isset($user['Password']) && !empty($user['Password'])) {
            // Directly compare the plain text password
            if ($password === $user['Password']) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_type'] = 'user'; // Set session for user

                // Check if the record already exists in the login table
                $sql_check_login = "SELECT * FROM login WHERE email = ?";
                $stmt_check_login = $conn->prepare($sql_check_login);

                if (!$stmt_check_login) {
                    die('Error preparing statement: ' . $conn->error);
                }

                $stmt_check_login->bind_param("s", $email);
                $stmt_check_login->execute();
                $result_check_login = $stmt_check_login->get_result();

                // Insert new record if no existing record found
                if ($result_check_login->num_rows === 0) {
                    $sql_login = "INSERT INTO login (email, password) VALUES (?, ?)";
                    $stmt_login = $conn->prepare($sql_login);

                    if ($stmt_login) {
                        $stmt_login->bind_param("ss", $email, $password);
                        $stmt_login->execute();
                        $stmt_login->close();
                    } else {
                        die('Error preparing statement: ' . $conn->error);
                    }
                }

                $stmt_check_login->close();

                $message = 'Login successful!';
                $message_type = 'success';
                header('Location: index.php');
                exit();
            } else {
                $message = 'Invalid password.';
                $message_type = 'error';
            }
        } else {
            // If password is missing
            $message = 'Password is missing or invalid.';
            $message_type = 'error';
        }
    } else {
        // If no user found
        $message = 'No user found. Signup first!';
        $message_type = 'error';
    }

    $stmt_check->close();
    $conn->close();

    if ($message) {
        echo "<script>showToast('$message', '$message_type');</script>";
    }
}
?>


</body>
</html>