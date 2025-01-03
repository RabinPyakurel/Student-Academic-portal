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
            <h2>Upcoming Events</h2>
            <div class="carousel">
                <div class="carousel-track">
                    <div class="carousel-item">
                        <img src="./assets/images/EVENT.jpeg" alt="Event 1">
                        <h3>HACKATHON</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/images/EVENT2.jpeg" alt="Event 2">
                        <h3>KATHFORD SPARKS</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/images/EVENTS3.jpeg" alt="Event 3">
                        <h3>FAREWELL PROGRAM</h3>
                        <p><a href="./events/events.php">Click to view more details about the event.</a></p>
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/images/events4.jpeg" alt="Event 3">
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
            <h2>Streamlining Your Academic Journey</h2>
            <p>
                Student Academic Portal (SAPO) is a secure platform developed exclusively for Kathford International
                College. Our mission is to simplify your academic processes and provide features like marks tracking,
                event notifications, and online exam registration.
            </p>
        </div>
    </section>

    <!-- Features Section -->
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
                    <img src="/assets/images/attendance.jpg" alt="Attendance Tracking">
                    <h3>Attendance Tracking</h3>
                    <p>Track your attendance regularly.</p>
                </div>
                <div class="card">
                    <img src="/assets/images/exam-form.jpeg" alt="Online Exam Registration">
                    <h3>Online Exam Registration</h3>
                    <p>Register for exams from the comfort of your home.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'layout/footer.htm' ?>
    <script src="./assets/js/index.js"></script>
</body>

</html>