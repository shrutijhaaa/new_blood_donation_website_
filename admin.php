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
    <title>Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
        <div class="logo">
            <img src="images/logo1.jpg" alt="Logo">
        </div>
           <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <!-- Display user's initial in a styled div -->
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
                <li><a href="Doner_list.php">Doner List</a></li>
                <li><a href="Requested_blood.php">Request for Blood</a></li>
            </ul>
        </div>
        <div class="dashboard">
            <div class="card red">
                <p>Listed Blood Groups</p>
                <a href="listed_blood.php">Full Detail →</a>
            </div>
            <div class="card green">
                <p>Registered Blood Group</p>
                <a href="blood_group.php">Full Detail →</a>
            </div>
            <div class="card blue">
                <p>Total Blood Request Received</p>
                <a href="Requested_blood.php">Full Detail →</a>
            </div>
        </div>
</body>
</html>
