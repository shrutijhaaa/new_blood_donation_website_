<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

?>
<?php
// Include the database configuration file
include 'db_config.php';

// Fetch ID, Name, and Gender from the donate table
$sql = "SELECT id, Name, Gender FROM donate";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor List</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            overflow-y: hidden;
        }
    
        table {
            margin-left:-100%;
            width: 300%;
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
            color: red;
        }
    </style>
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
            <div class="table-container">
            <h1>Donor List</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
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
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
