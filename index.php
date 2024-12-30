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
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                    <img src="./assets/images/Student.png" alt="Student Academic Portal">
            </div>
            <button class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-links" id="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="./about us/aboutus.htm">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#program">Programs</a></li>
                <li>
                    <a id="register" class="register-btn">Register</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="login-dropdown">Login</a>
                    <ul class="dropdown-menu">
                        <li><a class="register-btn" id="login-admin" href="./authentication/">Login as Admin</a></li>
                        <li><a class="register-btn" id="login-user" href="./authentication/sign-in.php">Login as
                                Student</a></li>
                    </ul>
                </li>
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
        <div class="container">
            <h2>Streamlining Your Academic Journey</h2>
            <p>
                Student Academic Portal (SAPO) is a secure platform developed exclusively for Kathford International
                College. <br>
                Our mission is to simplify your academic processes and provide features like marks tracking, event
                notifications, and online exam registration.
            </p>
            <a href="./about us/aboutus.htm" class="btn-about">About us</a>
        </div>
    </section>


    <section id="features" class="features">
        <div class="container">
            <h2>Our Features</h2>
            <div class="card-container">
                <div class="card">
                    <img src="/assets/images/marks.jpeg" alt="Marks Tracking">
                    <h3>Marks Tracking</h3>
                    <p>Easily monitor your academic progress.</p>
                </div>
                <div class="card">
                    <img src="/assets/images/attendance.jpg" alt="Event Notifications">
                    <h3>Attendance Tracking</h3>
                    <p>Track your Attendance on regular basis.</p>
                </div>
                <div class="card">
                    <img src="/assets/images/exam-form.jpeg" alt="Online Exam Registration">
                    <h3>Online Exam Registration</h3>
                    <p>Register for exams from the comfort of your home.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="program" class="program-carousel">
        <div class="container">
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
    <footer id="contact" class="contact">
        <div class="nav-container">
            <p>&copy; 2024 Student Academic Portal. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="./assets/js/index.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>