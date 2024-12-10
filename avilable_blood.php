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
    <title>Search for Available Blood</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="avilable_blood.css?v=1.0"> <!-- Link to your CSS file -->
   
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

    <div class="container">
        <h2>Search for Available Donors</h2>
        <form method="POST">
            <label for="state">State:</label>
            <select id="state" name="state" required>
               <option value="">Select State</option>
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
                    <option value="state1">Rajasthan</option>
                    <option value="state2">Uttar Pradesh</option>
                    <option value="state1">Delhi</option>
                    <option value="state2">Tamil Nadu</option>
                
            </select>
    
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
    
            <label for="blood_group">Blood Group:</label>
            <select id="blood_group" name="blood_group" required>
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
    
            <label for="reason">Reason for Needing Blood:</label>
            <textarea id="reason" name="reason" placeholder="Please specify why you need the blood..." required></textarea>
    
            <input type="submit" name="search" value="Search Donors">
        </form>



<!-- Output Container -->
<div class="output-container">
    <?php
    if (isset($_POST['search'])) {
        require_once 'db_config.php'; // Include your database connection

        // Retrieve form data
        $blood_group = isset($_POST['blood_group']) ? $_POST['blood_group'] : '';

        // Prepare and execute the SQL query
        $sql = "SELECT name, age, gender,mobile_number,state,address, blood_group 
                FROM donate 
                WHERE blood_group = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $blood_group);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display the results
        if ($result->num_rows > 0) {
            echo "<h3>Available Donors</h3>";
            echo "<table>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Mobile Number</th>
                        <th>State</th>
                        <th>Address</th>
                        <th>Blood Group</th>
                    </tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['name'] . "</td>
                        <td>" . $row['age'] . "</td>
                        <td>" . $row['gender'] . "</td>
                        <td>" . $row['mobile_number'] . "</td>
                        <td>" . $row['state'] . "</td>
                        <td>" . $row['address'] . "</td>
                        <td>" . $row['blood_group'] . "</td>
                      </tr>";
            }
            echo "</table>";

            // Add button at the bottom of the table
            echo "<div class='button-container'>
                    <a href='blood_booking.php'>
                        <button>Book blood</button>
                    </a>
                  </div>";
        } else {
            echo "<p>No donors found matching your criteria.</p>";
        }

        // Close connection
        $stmt->close();
        $conn->close();
    }
    ?>
  
 
</div>
    </div>
 <div class="footer">
        &copy; 2024 Blood Donation Website | All rights reserved.
    </div>
</body>
</html>
