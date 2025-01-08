<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    include 'not-found.htm';
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
    <link rel="stylesheet" href="./assets/css/home.css">
</head>
<body>
    <?php include 'layout/nav.htm' ?>
    <!-- Hero Section (Carousel) -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Streamline Academics with SAPO</h1>
            <p>Your academic life, simplified and organized.</p>
            <a href="#features" class="btn">View Services</a>
        </div>
    </section>

    <!-- Events Carousel Section -->
    <section id="events" class="events-carousel">
        <div class="container">

                <h2>Exciting Events Ahead</h2>
            
            <div class="carousel">
                <div class="carousel-track">
                    <div class="carousel-item">
                        <img src="./assets/images/eventsimage/6.jpg" alt="Event 1">
                        <h3>TREASURE HUNT</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/images/eventsimage/2.png" alt="Event 2">
                        <h3>UI/UX WORKSHOP</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/images/eventsimage/3.png" alt="Event 3">
                        <h3>FAREWELL PROGRAM</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/images/eventsimage/4.png" alt="Event 3">
                        <h3>QUIZ COMPETITION</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                </div>
                <button class="carousel-btn prev" id="prev-btn">&lt;</button>
                <button class="carousel-btn next" id="next-btn">&gt;</button>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2>Empowering Your Academic Journey</h2>
            <p>
            Effortlessly track your marks, stay informed with event updates, and register for exams online—all on a secure, intuitive platform.
            </p>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h2>Our Features</h2>
            <div class="card-container">
                <div class="card">
                    <img src="/assets/images/fee-removebg-preview.png" alt="fee management">
                    <h3>Fee Management</h3>
                    <p>Check fee payment status,due dates and Pay fees online.</p>
                </div>
                <div class="card">
                    <img src="/assets/images/attendance.jpg" alt="Event Notifications">
                    <h3>Attendance Tracking</h3>
                    <p>Track your Attendance on regular basis.</p>
                </div>
                <div class="card">
                    <img src="/assets/images/examresult.png" alt="Online Exam Registration">
                    <h3>Exam Registration and Results</h3>
                    <p>Register for exams online and access detailed results from your comfort place.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="back-to-top">
     <button id="back-to-top" onclick="scrollToTop()">Back to Top ↑ </button>
    </section>
    <?php include 'layout/footer.htm' ?>
    <script src="./assets/js/index.js"></script>
    <script>
window.onscroll = function () {
  const button = document.getElementById("back-to-top");
  if (document.documentElement.scrollTop > 200) {
    button.style.display = "block";
  } else {
    button.style.display = "none";
  }
};


function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
}

    </script>
</body>

</html>