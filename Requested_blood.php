<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Include the database configuration file
include 'db_config.php';

// Include PHPMailer files
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$toast_message = ''; // To hold the toast message
$toast_type = '';    // To hold the toast type (success or error)

// Update status for all records if the admin has submitted the form
if (isset($_POST['update_all'])) {
    foreach ($_POST['status'] as $id => $new_status) {
        // Update the status in the database
        $sql = "UPDATE book_blood SET Status='$new_status' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            // Fetch user's email based on the ID
            $query = "SELECT Email FROM book_blood WHERE id='$id'";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();

            if ($row && isset($row['Email'])) {
                $email = $row['Email'];

                // Send an email if the status is "Available blood"
                if ($new_status == 'Available blood') {
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'jhashruti932@gmail.com';
                        $mail->Password   = 'rrbe dzkz qxrv luwh';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        // Recipients
                        $mail->setFrom('jhashruti932@gmail.com', 'Blood Donation');
                        $mail->addAddress($email);  // Add a recipient

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Blood Donation Request Update';
                        $mail->Body    = 'Your request for blood donation has been processed. You can now visit to get the blood you requested.';

                        $mail->send();
                    } catch (Exception $e) {
                        // Set error toast message if email fails to send
                        $toast_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        $toast_type = "error";
                    }
                }
            }
        }
    }

    // Set success toast message
    $toast_message = "Status updated successfully!";
    $toast_type = "success";
}

// Fetch all data from the book_blood table
$sql = "SELECT * FROM book_blood";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Booking List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            overflow-y: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: red;
        }
        .update-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            border-radius: 0.5rem;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .update-button:hover {
            background-color: red;
        }
        /* Toast Styles */
        .toast {
            visibility: hidden;
            max-width: 50%;
            height: 50px;
            margin: 0 auto;
            position: fixed;
            left: 50%;
            top: 30px; /* Move it to the top */
            font-size: 17px;
            background-color: #333;
            color: #fff;
            padding: 16px;
            border-radius: 5px;
            z-index: 1000;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s linear;
        }
        .toast.success {
            background-color: #4CAF50;
        }
        .toast.error {
            background-color: #f44336;
        }
        .toast.show {
            visibility: visible;
            opacity: 1;
        }
    </style>

</head>
<body>
<header>
    <div class="logo">
        <img src="images/logo1.jpg" alt="Logo">
    </div>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <a href="logout.php" class="fas fa-user-alt"></a>
    <?php else: ?>
        <a href="login.php" class="">Login</a>
    <?php endif; ?>
</header>

<div class="container">
    <div class="sidebar">
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="blood_group.php">Available Blood</a></li>
            <li><a href="Doner_list.php">Donor List</a></li>
            <li><a href="Requested_blood.php">Request for Blood</a></li>
        </ul>
    </div>
    <div class="dashboard">
        <div class="table-container">
            <h1>Requested Blood</h1>
            <form method="POST" action="Requested_blood.php">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Blood Group</th>
                        <th>State</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['Phone_Number'] . "</td>";
                            echo "<td>" . $row['Blood_Group'] . "</td>";
                            echo "<td>" . $row['State'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>" . $row['Time'] . "</td>";
                            echo "<td>";
                            echo "<select name='status[" . $row['id'] . "]'>";
                            echo "<option value='Pending'" . ($row['Status'] == 'Pending' ? ' selected' : '') . ">Pending</option>";
                            echo "<option value='Available blood' ". ($row['Status'] == 'Available blood' ? ' selected' : '') . ">Available blood</option>";
                            echo "</select>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No records found</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <input type="submit" name="update_all" value="Update All" class="update-button">
            </form>
        </div>
    </div>
</div>

<!-- Toast message -->
<div id="toast" class="toast <?php echo $toast_type; ?>"><?php echo $toast_message; ?></div>

<script>
    // Show toast if there is a message
    var toastMessage = "<?php echo $toast_message; ?>";
    if (toastMessage) {
        var toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(function () {
            toast.classList.remove('show');
        }, 5000); // Hide toast after 5 seconds
    }
</script>
</body>
</html>
