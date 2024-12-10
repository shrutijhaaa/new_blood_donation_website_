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

// Handle form submission to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statuses = $_POST['status'];
    $ids = $_POST['id'];
    $statusUpdated = false;

    // Email setup
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jhashruti932@gmail.com'; // Your Gmail address
    $mail->Password   = 'rrbe dzkz qxrv luwh'; // Your Gmail account password
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('jhashruti932@gmail.com', 'Blood Donation');

    foreach ($ids as $index => $id) {
        $status = $statuses[$index];

        // Update status in database
        $updateSql = "UPDATE donate SET Status = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param('si', $status, $id);

        if ($stmt->execute()) {
            $statusUpdated = true;

            if ($status === 'Donation Done') {
                // Send email
                $stmt = $conn->prepare("SELECT Email FROM donate WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $mail->addAddress($row['Email']); // Use donor's email from the database
                $mail->Subject = 'Donation Status Updated';
                $mail->Body    = 'Congratulations! Your donation has been marked as "Donation Done". Thank you for your contribution!';

                if ($mail->send()) {
                    echo '<script>toastr.success("Donation status updated and email sent.");</script>';
                } else {
                    echo '<script>toastr.error("Failed to send email.");</script>';
                }
                $mail->clearAddresses(); // Clear recipient after each send
            }
        } else {
            echo '<script>toastr.error("Failed to update donation status.");</script>';
        }
    }

    if (!$statusUpdated) {
        echo '<script>toastr.error("No status updates made.");</script>';
    }
}

$sql = "SELECT * FROM donate";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations List</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Toastr CSS for toast messages -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
        select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
        .center button {
            background-color: blue; /* Button color */
            color: white;
            border: none;
            border-radius: 0.5rem; /* Circular button */
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s; /* Smooth color transition */
        }
        .center button:hover {
            background-color: red; /* Hover color */
        }
        /* Position toastr at the top center */
        #toast-container {
            top: 0;
            left: 60%;
            transform: translateX(-50%);
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>

<header>
    <div class="logo">
        <img src="images/logo1.jpg" alt="Logo">
    </div>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <a href="logout.php" class="fas fa-user-alt"></a>
    <?php else: ?>
        <a href="login.php">Login</a>
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
            <h1>All Donors</h1>
            <form method="post" action="">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Blood Group</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>State</th>
                            <th>District</th>
                            <th>Pin Code</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th> <!-- New Status Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['Name'] . "</td>";
                                echo "<td>" . $row['Gender'] . "</td>";
                                echo "<td>" . $row['Age'] . "</td>";
                                echo "<td>" . $row['Blood_group'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['Mobile_number'] . "</td>";
                                echo "<td>" . $row['State'] . "</td>";
                                echo "<td>" . $row['District'] . "</td>";
                                echo "<td>" . (!empty($row['Pin code']) ? $row['Pin code'] : 'N/A') . "</td>";
                                echo "<td>" . $row['Address'] . "</td>";
                                echo "<td>" . (!empty($row['Date']) ? $row['Date'] : 'N/A') . "</td>";
                                echo "<td>" . (!empty($row['Time']) ? $row['Time'] : 'N/A') . "</td>";
                                echo "<td>";
                                echo "<input type='hidden' name='id[]' value='" . $row['id'] . "'>";
                                echo "<select name='status[]'>";
                                echo "<option value='Pending' " . ($row['Status'] === 'Pending' ? 'selected' : '') . ">Pending</option>";
                                echo "<option value='Donation Done' " . ($row['Status'] === 'Donation Done' ? 'selected' : '') . ">Donation Done</option>";
                                echo "</select>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='14'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="center">
                    <button type="submit">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Close the database connection
$conn->close();
?>

<script>
$(document).ready(function() {
    <?php if (isset($statusUpdated) && $statusUpdated): ?>
        toastr.success("Donation status updated.");
    <?php elseif (isset($statusUpdated) && !$statusUpdated): ?>
        toastr.error("No status updates made.");
    <?php endif; ?>
});
</script>

</body>
</html>
