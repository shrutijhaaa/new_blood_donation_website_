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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=1.0"/>
    <title>Blood Donation</title>
</head>
<body>
    <div class="container">
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
<div class="para">
    <p>"Don't feel weak about yourself,<span> you have the ability</span><br>
    to gave chance to someone.<span> donate the blood."</span></p>
<div class="button">
    <button type="button" onclick="window.location.href='who_can_donate.php';">DONATE</button>
</div>
</div>


<div class="about_container">
<div class="image">
    <img src="images/save life.jpg" height="300">
<div class="about">
    <h1>Our Service</h1>
    <p> our mission is to save lives by encouraging <br>
        and facilitating blood donation. Every day, <br>
        countless individuals rely on blood donations <br>
        for surgeries,cancer treatments, chronic illnesses,<br>
        and traumatic injuries.Your generous contribution <br>
        can make a significant difference and save lives.<br>
        ensuring that every contribution makes a tangible impact.<br>
        With a commitment to safety, reliability, and community,<br>
        we strive to create a future where access to life-saving <br>
        blood is never a concern. Join us in this vital journey<br>
        towards a healthier, more resilient society.</p>
</div>
</div>
</div>

<!-- scrollable website  -->
 <div class="container-wrapper">
    <div class="scroll-container">
      <div class="box" onclick="showDetails(this)">
        <h2>Why Donate Blood?</h2>
        <img src="images/why_donate.jpg" alt="Image 1">
        <p>Discover the life-saving impact of your blood donation. Each pint can save up to three lives. Learn about the critical need for blood and how your donation can make a difference.</p>
        <button class="learn-more" onclick="window.location.href='steps.php';">Learn More</button>
      </div>
      <div class="box" onclick="showDetails(this)">
        <h2> Who Can Donate?</h2>
        <img src="images/who_donate.jpg" alt="Image 2">
        <p>Understand the eligibility criteria for blood donation. Find out if you meet the requirements to donate blood and what you need to do to prepare.</p>
        <button class="learn-more"onclick="window.location.href='who_can_donate.php';">Learn More</button>
      </div>
      <div class="box" onclick="showDetails(this)">
        <h2>The Donation Process</h2>
        <img src="images/donation process.jpg" alt="Image 3">
        <p> Learn about the simple and quick steps involved in donating blood. From registration to post-donation care, we guide you through the process to ensure a smooth experience.</p>
        <button class="learn-more"onclick="window.location.href='steps.php';">Learn More</button>
      </div>
      <div class="box" onclick="showDetails(this)">
        <h2>Testimonials</h2>
        <img src="images/testimonial.jpg" alt="Image 4">
        <p>Read inspiring stories from donors and recipients. See how blood donations have positively impacted lives and communities.</p>
        <button class="learn-more"onclick="window.location.href='about.php';">Learn More</button>
      </div>
    </div>
    <div class="background-image">

    </div>
 
</div>
  
  <div class="back">
    <h1>Available Blood Groups</h1>
    <div class="Blood">
    <div class="blood-group" id="a-positive">A+</div>
    <div class="blood-group" id="a-negative">A-</div>
    <div class="blood-group" id="b-positive">B+</div>
    <div class="blood-group" id="b-negative">B-</div>
    <div class="blood-group" id="ab-positive">AB+</div>
    <div class="blood-group" id="ab-negative">AB-</div>
    <div class="blood-group" id="o-positive">O+</div>
    <div class="blood-group" id="o-negative">O-</div>
    </div>
</div>
<!--Event camp-->
<h1>Blood Donation Camp</h1>
<div class="event-background">

<div class="event-wrapper">
    
    <div class="event-scroller">
        <?php
        include('db_config.php');

        // Correct query using the 'blood_donation_camp' table name
        $query = "SELECT id, event_name, location, date, time, description FROM blood_donation_camp WHERE status='live' ORDER BY date ASC LIMIT 8";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $events = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $events[] = $row;
            }

            foreach ($events as $event) {
                echo '<div class="event-box">';
                echo '<h2>' . htmlspecialchars($event['event_name']) . '</h2>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($event['location']) . '</p>';
                echo '<p><strong>Date:</strong> ' . date('F d, Y', strtotime($event['date'])) . '</p>';
                echo '<p><strong>Time:</strong> ' . date('h:i A', strtotime($event['time'])) . '</p>';
                echo '<p>' . htmlspecialchars($event['description']) . '</p>';
                echo '<a href="donate.php?event_id=' . $event['id'] . '" class="event-learn-more">Donate</a>';
                echo '</div>';
            }
        }
        ?>
   </div>
    </div>
</div>

<!--knowlege-->
<section class="donation-info">
    <div class="image-container">
        <img src="images/bloodbank.jpg" alt="Blood Bag">
        <div class="text-overlay">
            <p>One Blood Donation can save up to <span>Three Lives</span></p>
        </div>
    </div>
    <div class="compatibility-table">
        <h2>LEARN ABOUT DONATION</h2>
        <h3>Compatible Blood Type Donors</h3>
        <table>
            <tr>
                <th>Blood Type</th>
                <th>Donate Blood To</th>
                <th>Receive Blood From</th>
            </tr>
            <tr>
                <td>A+</td>
                <td>A+ AB+</td>
                <td>A- A+ O- O+</td>
            </tr>
            <tr>
                <td>O+</td>
                <td>O+ A+ B+ AB+</td>
                <td>O- O+</td>
            </tr>
            <tr>
                <td>B+</td>
                <td>B+ AB+</td>
                <td>B- B+ O- O+</td>
            </tr>
            <tr>
                <td>AB+</td>
                <td>AB+</td>
                <td>Everyone</td>
            </tr>
            <tr>
                <td>A-</td>
                <td>A+ AB- AB+</td>
                <td>A- O-</td>
            </tr>
            <tr>
                <td>O-</td>
                <td>Everyone</td>
                <td>O-</td>
            </tr>
            <tr>
                <td>B-</td>
                <td>B+ AB- AB+</td>
                <td>B- O-</td>
            </tr>
            <tr>
                <td>AB-</td>
                <td>AB- AB+</td>
                <td>AB- A- B- O-</td>
            </tr>
        </table>
    </div>
</section>
<!--footer section!-->
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>Company</h3>
            <ul>
                <li><a href="about.php">About Us</a></li>
                <li><a href="#">Our Mission</a></li>
                <li><a href="who_can_donate.php">Terms & Conditions</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Get Help</h3>
            <ul>
               <li><a href="contact.php">Contact Us</a></li>
                <li><a href="steps.php">Donation Process</a></li>
                <li><a href="who_can_donate.php">Eligibility</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Blood Donation</h3>
            <ul>
                <li><a href="steps.php">Why Donate Blood</a></li>
                <li><a href="steps.php">Donation Centers</a></li>
                <li><a href="about.php">Success Stories</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com/"><img src="images/facebook.jpg" alt="Facebook" class="social-icon"></a>
                <a href="https://x.com/login="><img src="images/twitter.png" alt="Twitter" class="social-icon"></a>
                <a href="https://www.instagram.com/"><img src="images/instagram.jpg" alt="Instagram" class="social-icon"></a>
                <a href="https://www.linkedin.com/feed/"><img src="images/linkedin.jpg" alt="LinkedIn" class="social-icon"></a>
            </div>
        </div>
    </div>
</footer>
<?php include 'chatboat.html'; ?>
<div class="event-container" id="event-container">
    <!-- Events will be loaded here via AJAX -->
</div>

</div>
</body>
</html>