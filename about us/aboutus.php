<?php
session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="/assets/css/aboutus.css">
  <link href='https://fonts.googleapis.com/css?family=Alegreya SC' rel='stylesheet'>

</head>

<body>
<?php include '../layout/nav.htm' ?> 

  <section id="home" class="hero">
    <div class="hero-content">
      <h1>About Us</h1>
      <p>Empowering students with easy access to their academic information.</p>
    </div>
  </section>


  <div class="about-page">
    <div class="about-section">
      <img src="../assets/images/360_F_918326857_hOWDSwwVzwlFn3FBAJgLQbdGJ0rAoE1G.jpg" alt="Our Mission"
        class="section-image">
      <div class="section-content">
        <h2>Our Mission</h2>
        <p>Our mission is to enhance student engagement and streamline academic processes by providing a centralized
          platform where students can access and manage their academic information.</p>
      </div>
    </div>
    <div class="about-section reverse">
      <img src="../assets/images/student-hero5.webp" alt="What We Offer" class="section-image">
      <div class="section-content">
        <h2>What We Offer</h2>
        <ul class="offer-li">
          <li class="offer-li"><strong>Real-Time Access to Academic Records:</strong> Easily view attendance, grades,
            and other academic
            details in one place.</li>
          <li class="offer-li"><strong>Personal Information Management:</strong> Update personal details such as contact
            information.
          </li>
          <li class="offer-li"><strong>Enhanced Communication:</strong> Stay informed with notifications, announcements,
            and updates.
          </li>
        </ul>
      </div>
    </div>

    <div class="about-section">
      <img src="../assets/images/download.jpeg" alt="Our Vision" class="section-image">
      <div class="section-content">
        <h2>Our Vision</h2>
        <p>We envision a digital academic environment where every student has access to the resources they need,
          fostering a seamless and supportive educational experience.</p>
      </div>
    </div>

    <div class="about-section reverse">
      <img src="../assets/images/images.jpeg" alt="Why Choose Us" class="section-image">
      <div class="section-content">
        <h2>Why Choose Us?</h2>
        <p>Designed with students in mind, our portal offers a responsive and user-friendly experience, accessible on
          any device. Our commitment to continuous improvement makes us a reliable academic companion.</p>
      </div>
    </div>
  </div>
<?php include '../layout/footer.htm' ?>
</body>

</html>