<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location:home.php') ;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Academic Portal</title>
  <link rel="stylesheet" href="./assets/css/index.css">
  <link rel="stylesheet" href="./assets/css/nav.css">
  <style>
  .nav-links {
    gap: 4rem;
  }

  .dropdown .dropdown-menu {
    margin-right: -20%;
  }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="nav-container">
      <div class="logo">
        <img src="./assets/images/final-logo.png" alt="Student Academic Portal">
      </div>
      <button class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <ul class="nav-links" id="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="./about us/indexaboutus.php">About</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#program">Programs</a></li>
        <li>
          <a id="register" class="register-btn">Register</a>
        </li>
        <li><a class="register-btn" id="login-user" href="./authentication/sign-in.php">Login </a></li>
      </ul>
    </div>
  </nav>
  <section id="home" class="hero">
    <div class="hero-content">
      <h1>Streamline Academics with SAPO</h1>
      <p>Your academic life, simplified and organized.</p>
      <a href="#features" class="btn">View features</a>
    </div>
  </section>


  <section id="about" class="about">
    <div class="content-container">
      <h2>Streamlining Your Academic Journey</h2>
      <p>
        Student Academic Portal (SAPO) is a secure platform developed exclusively for Kathford International
        College. <br>
        Our mission is to simplify your academic processes and provide features like marks tracking, event
        notifications, and online exam registration.
      </p>
      <a href="./about us/indexaboutus.php" class="btn-about">About us</a>
    </div>
  </section>


  <section id="features" class="features"><br><br>
    <div class="content-container">
      <h2>Our Features</h2>
      <div class="card-container">
        <div class="card" id="signUpButton">
          <img src="/assets/images/fee-removebg-preview.png" alt="fee management">
          <h3>Fee Management</h3>
          <p>Check fee payment status,due dates and Pay fees online.</p>
        </div>
        <div class="card" id="signUpButton">
          <img src="/assets/images/attendance.jpg" alt="Event Notifications">
          <h3>Attendance Tracking</h3>
          <p>Track your Attendance on regular basis.</p>
        </div>
        <div class="card" id="signUpButton">
          <img src="/assets/images/examresult.png" alt="Online Exam Registration">
          <h3>Exam Registration and Results</h3>
          <p>Register for exams online and access detailed results from your comfort place.</p>
        </div>
      </div>
    </div>
  </section>



  <section id="program" class="program-carousel"><br><br>
    <div class="content-container">
      <h2>Our Programs</h2>
      <div class="carousel">
        <div class="carousel-track">
          <div class="carousel-item">
            <img src="./assets/images/bca.jpg" alt="bca">
            <h3>BCA</h3>
            <p>Stay updated with your academic progress effortlessly.</p>
          </div>

          <div class="carousel-item">
            <img src="./assets/images/bba.jpg" alt="bba">
            <h3>BBA</h3>
            <p>Never miss out on important academic events.</p>
          </div>

          <div class="carousel-item">
            <img src="./assets/images/csit.jpg" alt="bsc csit">
            <h3>Bsc CSIT</h3>
            <p>Register for exams from the comfort of your home.</p>
          </div>
          <div class="carousel-item">
            <img src="./assets/images/bbm.jpg" alt="bbm">
            <h3>BBM</h3>
            <p>Register for exams from the comfort of your home.</p>
          </div>
          <div class="carousel-item">
            <img src="./assets/images/civil.jpeg" alt="be civil">
            <h3>BE Civil</h3>
            <p>Register for exams from the comfort of your home.</p>
          </div>
          <div class="carousel-item">
            <img src="./assets/images/computer-engineering.jpg" alt="be computer">
            <h3>BE Computer</h3>
            <p>Register for exams from the comfort of your home.</p>
          </div>
        </div>

        <button class="carousel-btn prev" id="prev-btn">&lt;</button>
        <button class="carousel-btn next" id="next-btn">&gt;</button>
      </div>
    </div>
  </section>
  <div id="faq-container">
    <button id="faq-btn" class="faq-btn">FAQ?</button>
    <div id="faq-modal" class="faq-modal">
      <div class="faq-modal-content">
        <button class="faq-close-btn" id="faq-close-btn">&times;</button>
        <iframe src="/layout/faqs.htm" id="faq-iframe"></iframe>
      </div>
    </div>
  </div>

  <footer>
    <div class="footer-section">
      <div class="footer-column">
        <h3>Contact Information</h3>
        <p><strong>Faculty of Engineering</strong></p>
        <p>01-5201241</p>
        <p>01-5201241</p>
        <p>info@college.edu.np</p>
        <a href="#">location</a>
      </div>
      <div class="footer-column">
        <p><strong>Faculty of IT and Management</strong></p>
        <p>01-5201241</p>
        <p>01-5201241</p>
        <p>info@college.edu.np</p>
        <a href="#">location</a>
      </div>
      <div class="footer-column">
        <h3>Information</h3>
        <p><a href="/about us/indexaboutus.php">About Us</a></p>
        <p><a href="">Events</a></p>
      </div>
      <div class="footer-column">
        <h3>Follow us</h3>
        <div class="social-icons">
          <a href="#"><img src="/assets/images/facebook.png" alt="Facebook"></a>
          <a href="#"><img src="/assets/images/instagram.png" alt="Instagram"></a>
          <a href="#"><img src="/assets/images/linkedin.png" alt="LinkedIn"></a>
          <a href="#"><img src="/assets/images/twitter.png" alt="Twitter"></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2024 All rights reserved - <a href="#">Privacy</a> - <a href="/layout/terms.htm">Terms and Conditions</a></p>
    </div>
  </footer>
  <script src="/assets/js/faqs.js"></script>

  <script src="./assets/js/index.js"></script>
  <script src="./assets/js/nav.js"></script>
  <script src="./assets/js/TESTIMONIAL.js"></script>
</body>

</html>