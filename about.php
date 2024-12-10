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
    <title>About Us - Blood Donation</title>
    <link rel="stylesheet" href="about.css">

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
                        <a href="logout.php"class="fas fa-user-alt"></a>
                    <?php else: ?>
                        <a href="login.php" class="">Login</i></a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </header>
    <div class="about-section">
        <div class="blood-drops">
            <img src="images/blooddrop1.jpg" alt="Blood Drop"  class="drop drop1">
            <img src="images/blooddrop1.jpg" alt="Blood Drop" class="drop drop2">
            <img src="images/blooddrop1.jpg" alt="Blood Drop" class="drop drop3">
        </div>
        <div class="text-content">
            <h1>Blood Donation</h1>
            <h2>About us</h2>
            <p> we are dedicated to saving lives through the simple yet profound act of blood donation. Our mission is to connect generous donors with those in need, ensuring that every patient has access to life-saving blood. We provide a seamless and supportive platform for donors to register, learn about the importance of blood donation, and find local donation events.

                Join us in making a difference—your donation can be the gift of life to someone in need. Together, we can save lives, one drop at a time.</p>                
            </div>
            </div>
        <div class="about-info">
           <p>
                    <strong>Who We Are:</strong><br>
                    We are a dedicated team of healthcare professionals, volunteers, and advocates committed to promoting the importance of blood donation. Our organization operates with the highest standards of integrity and efficiency, ensuring that every donation is used effectively and ethically.
                </p>
                <p>
                    <strong>Our History:</strong><br>
                    Founded in [Year], our organization has grown from a small local initiative to a nationwide network. Over the years, we have organized thousands of blood drives, partnered with numerous hospitals, and helped countless patients receive the life-saving blood they need. Our journey has been fueled by the generosity of donors and the tireless efforts of our volunteers.
                </p>
                </div>
            <!--four container-->
            <h1>Life-Saving Story</h1>
            <div class="hero-image">
                
                <div class="grid-container">
                <div class="container">
                    <h2>A Life-Saving Donation</h2>
                    <p>John sat nervously in the donation chair, but felt proud as his blood flowed into the bag. His donation would later save a young girl in a car accident. His simple act made a huge difference.</p>
                </div>
                <div class="container">
                    <h2>A Community Effort</h2>
                    <p>In a small town, everyone came together for a blood drive. Neighbors and friends contributed, resulting in enough blood to help patients for weeks. It was a powerful demonstration of community spirit.</p>
                </div>
                <div class="container">
                    <h2>The Unexpected Hero</h2>
                    <p>Mark didn't see himself as a hero, but his decision to donate blood at work saved a colleague's life. His quick action turned him into an unexpected hero, highlighting the impact of giving.</p>
                </div>
                <div class="container">
                    <h2>Generations of Giving</h2>
                    <p>The Lee family has a tradition of donating blood together every year. This practice instills the value of giving in the younger generation, continuing a legacy of compassion and care.</p>
                </div>
            </div>
            </div>
            <!-- fun fact-->
             <!-- Did You Know? Carousel -->


<!-- Did You Know? Carousel -->

<section class="fun-fact">
    <h2>Did You Know?</h2><br>
        <p>Fact 1: Blood makes up about 7-8% of your total body weight.</p>
            <p>Fact 2: One pint of blood can save up to three lives.</p>
            <p>Fact 3: The average person has about 10 pints of blood in their body.</p>
            <p>Fact 4: Blood donation reduces the risk of certain health issues in donors, such as hemochromatosis, by maintaining healthy iron levels in the body.</p>
            <p>Fact 5: Plasma, a component of blood, is used to treat patients with clotting disorders and can be donated more frequently than whole blood.</p>
            <p>Fact 6: Regular blood donors can donate whole blood every 56 days, allowing them to contribute multiple times a year.</p>
            <p>Fact 7: Blood cannot be manufactured or created in a lab, which is why donations are essential for medical treatments and emergencies.</p>
            <p>Fact 8: Less than 38% of the population is eligible to donate blood at any given time, making each donation incredibly valuable.</p>
            <p>Fact 9: Every two seconds, someone in the United States needs blood, highlighting the constant demand for donations.</p>
            <p>Fact 10: Type O negative blood is the universal donor type, meaning it can be transfused to patients of any blood type in emergencies.</p>
        </div>
</section>
<?php include 'chatboat.html'; ?>
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

</body>
</html>
