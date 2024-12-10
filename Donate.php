<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Include PHPMailer
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";
$message_type = "success";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'blood_donation');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Capture and sanitize form data
    $name = $conn->real_escape_string($_POST['name']);
    $age = $conn->real_escape_string($_POST['age']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile_number = $conn->real_escape_string($_POST['mobile_number']);
    $state = $conn->real_escape_string($_POST['state']);
    $district = $conn->real_escape_string($_POST['district']);
    $pin_code = $conn->real_escape_string($_POST['pin_code']);
    $address = $conn->real_escape_string($_POST['address']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);

    // Check for duplicate entries based on email and time
    $duplicate_check_email = "SELECT * FROM donate WHERE email = '$email'";
    $result_email = $conn->query($duplicate_check_email);

    if ($result_email->num_rows > 0) {
        $message = "You have already registered with this email.";
        $message_type = 'error';
    } else {
        $duplicate_check_time = "SELECT * FROM donate WHERE time = '$time' AND email = '$email'";
        $result_time = $conn->query($duplicate_check_time);

        if ($result_time->num_rows > 0) {
            $message = "You have already registered with this email and time.";
            $message_type = 'error';
        } else {
            // Insert data into the database
            $sql = "INSERT INTO donate (Name, Age, Gender, blood_group, Email, mobile_number, State, District, Pin_code, Address, Date, Time) 
                    VALUES ('$name', '$age', '$gender', '$blood_group', '$email', '$mobile_number', '$state', '$district', '$pin_code', '$address', '$date', '$time')";

            if ($conn->query($sql) === TRUE) {
                $message = "Thank you for registering to donate blood!";

                // Send email notification using PHPMailer
                try {
                    $mail = new PHPMailer(true);

                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jhashruti932@gmail.com';
                    $mail->Password = 'rrbe dzkz qxrv luwh';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Email settings
                    $mail->setFrom('jhashruti932@gmail.com', 'Blood Donation Team');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Blood Donation Registration Confirmation';
                    $mail->Body = "Dear $name, thank you for registering to donate blood on $date at $time. Please visit your nearest blood donation camp. We can't wait to see you. Warm regards, The Blood Donation team!";

                    $mail->send();
                    $message .= " Email notification has been sent.";
                } catch (Exception $e) {
                    $message .= " Email notification failed: " . $mail->ErrorInfo;
                    $message_type = 'error';
                }
            } else {
                $message = "Error: There was an issue with your registration. Please try again.";
                $message_type = 'error';
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Sign-Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Donate.css">
    <style>
        /* Toast Message CSS */
        #toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 10%;
            transform: translateX(-50%);
            font-size: 17px;
        }

        #toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @keyframes fadein {
            from {top: 0; opacity: 0;} 
            to {top: 10%; opacity: 1;}
        }

        @keyframes fadeout {
            from {top: 10%; opacity: 1;} 
            to {top: 0; opacity: 0;}
        }

        /* Success and Error Styles */
        #toast.success {
            background-color:#4CAF50;
        }

        #toast.error {
            background-color: red;
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
                <li>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
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
    <div class="container">
        <!-- Toast Message -->
        <div id="toast" class="<?= $message_type ?>"><?= $message ?></div>

        <form action="Donate.php" method="POST">
            <div class="form-group">
                <label for="firstName">Name:</label>
                <input type="text" id="firstName" name="name" placeholder="Name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Value</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bloodGroup">Blood Group:</label>
                <select id="bloodGroup" name="blood_group" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="tel" id="mobile_number" name="mobile_number" placeholder="Mobile No." required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <select id="state" name="state" required>
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
                    <option value="state1">Punjab</option>
                    <option value="state2">Rajasthan</option>
                    <option value="state1">Tamil Nadu</option>
                    <option value="state2">Uttar Pradesh</option>
                    <option value="state2">West Bengal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="district">District:</label>
                <select id="district" name="district" required>
                    <option value="">Select District</option>
                    <option value="district1">Amaravati</option>
                    <option value="district2">Itanagar</option>
                    <option value="district1">Patna</option>
                    <option value="district2">Raipur</option>
                    <option value="district1">Gandhinagar</option>
                    <option value="district2">Panaji</option>
                    <option value="district1">Chandigarh</option>
                    <option value="district2">Shimla</option>
                    <option value="district1">Bhopal</option>
                    <option value="district2">Mumbai</option>
                    <option value="district1">Chandigarh</option>
                    <option value="district2">Lucknow</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pinCode">Pin Code:</label>
                <input type="text" id="pinCode" name="pin_code" placeholder="Pin Code" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="Address" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" id="time" name="time" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Donate</button>
            </div>
        </form>
    </div>
    <?php include 'chatboat.html'; ?>
    <script>
        // Show toast message if it's not empty
        window.onload = function() {
            var toastMessage = "<?= $message ?>";
            if (toastMessage !== "") {
                var toast = document.getElementById("toast");
                toast.className = "show <?= $message_type ?>";
                setTimeout(function() {
                    toast.className = toast.className.replace("show", "");
                }, 10000);
            }
        }
    </script>
</body>
</html>
