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
    <title>Blood Donation Infographic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="steps.css">
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
 <div class="infographic">
  
        <div class="video-container">
            <div class="video-wrapper">
                <video autoplay muted loop id="bg-video">
                    <source src="images/blood-vedio.mp4" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
            </div>
   
        <div class="step step1">
            <div class="number">01</div>
            <div class="text">Register<br>Sign up at a blood donation camp or online through a blood donation website.</div>
        </div>
        <div class="step step2">
            <div class="number">02</div>
            <div class="text">Screening<br>Undergo a health screening to ensure you are eligible to donate blood.</div>
        </div>
        <div class="step step3">
            <div class="number">03</div>
            <div class="text">Donation<br>Donate blood, which usually takes about 10-15 minutes.</div>
        </div>
        <div class="step step4">
            <div class="number">04</div>
            <div class="text">Rest & Refresh<br>Relax and have some refreshments to help your body recover.</div>
        </div>
        <div class="step step5">
            <div class="number">05</div>
            <div class="text">Recovery<br>Take it easy for the rest of the day and stay hydrated.</div>
        </div>
        <div class="step step6">
            <div class="number">06</div>
            <div class="text">Thank You<br>Your donation can save lives! Thank you for your contribution.</div>
        </div>
    </div>
   </div>
    <!--why should donate the blood-->
      
       <h1>why you should donate blood!</h1>
       <div class="hero-image">
        <div class="grid-container">
            <div class="donation-reason">
                <h3>Save Lives</h3>
                <p>A single blood donation can save up to three lives. Blood is essential for surgeries, cancer treatment, chronic illnesses, and traumatic injuries.</p>
            </div>
            <div class="donation-reason">
                <h3>Health Benefits</h3>
                <p>Donating blood can improve cardiovascular health and reduce the risk of cancer. Regular donations help in balancing iron levels in the body.</p>
            </div>
            <div class="donation-reason">
                <h3>Rare Blood Types</h3>
                <p>People with rare blood types can significantly impact patients with similar blood types, ensuring they receive the right type of blood during emergencies.</p>
            </div>
            <div class="donation-reason">
                <h3>Feel Good Factor</h3>
                <p>Helping others and contributing to the community can give donors a sense of pride and satisfaction.</p>
            </div>
            <div class="donation-reason">
                <h3>Free Health Check-up</h3>
                <p>Donors receive a mini physical, including checks on blood pressure, pulse, temperature, and hemoglobin levels, providing insights into their health.</p>
            </div>
    </div>
    </div>
    <div class ="camp">
       
<h1>Blood Donation Camps</h1>
        <div class="container">
            <div class="info-box">
                <h2>Find a Camp Near You</h2>
                <p>For those interested in donating, finding a Blood Donation Camp near you is simple. Many organizations and local health departments provide schedules and locations for upcoming camps. You can easily search online or check with your local health facilities to find the nearest camp in your state. Participating in a camp is a great way to meet others in your community who are committed to making a difference, and it provides an opportunity to donate blood in a safe and controlled environment.</p>
            </div>
    
            <div class="info-box">
                <h2>Join Us and Make a Difference</h2>
                <p>Blood Donation Camps play a crucial role in maintaining an adequate blood supply and are an excellent way for individuals to make a meaningful contribution to society. By attending a camp, you can help save lives and support the health of your community. So, find a camp near you, roll up your sleeve, and be a hero to those in need. Together, we can make a significant impact and ensure that lifesaving blood is always available for those who need it most.</p>
            </div>
    
            <div class="info-box">
                <h2>Encouragement to Participate</h2>
                <p>Your participation in Blood Donation Camps can make a real difference. Not only will you be helping those in urgent need of blood, but you will also be setting an example for others to follow. Each donation can save up to three lives, making your contribution incredibly valuable. Let's work together to create a healthier and more compassionate world through the simple yet profound act of blood donation.</p>
            </div>
        </div>
</div>
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
</body>
</html>
