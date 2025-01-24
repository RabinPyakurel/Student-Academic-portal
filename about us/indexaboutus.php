<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="../assets/css/aboutus.css">
  <link rel="stylesheet" href="../assets/css/nav.css">
  <link rel="stylesheet" href="../assets/css/index.css">
 

  <link href='https://fonts.googleapis.com/css?family=Alegreya SC' rel='stylesheet'>
  <style>
    .hero {
      background-image: url('../assets/images/aboutus.png');
      
    }
    .hero-content h1{
      font-size : 2.5rem;
      color: #132944;
      margin-top:-5vh;
      
    }
    .hero-content p{
      color: #132944;
      
    }
    
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="nav-container">
      <div class="logo">
        <img src="../assets/images/Student.png" alt="Student Academic Portal">
      </div>
      <button class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <ul class="nav-links" id="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="/index.php#features">Features</a></li>
        <li><a href="/index.php#programs">Programs</a></li>
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
      <h1>About Us</h1>
      <br><br><br>
      <p> This website is the culmination of the collaborative efforts of Rabin and Ritika, who turned their shared
        vision into reality.</p>

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
    <section class="team-section">
  <h2>Meet Our Dedicated Team</h2>
  <div class="team">

    <div class="team-member1">
      <img src="../assets/images/rabin.jpg" alt="Rabin" class="team-photo">
      <h3>Rabin Pyakurel</h3>
      <p>Rabin is the mastermind behind creating the backend structure and ensuring the smooth operation of the portal. <br> <br>With a strong background in programming and problem-solving, he worked on integrating key features like exam results, attendance tracking, and fee management into the platform.</p>
    </div>
    <div class="team-member2">
      <img src="../assets/images/ritika.jpg" alt="Ritika" class="team-photo">
      <h3>Ritika Suwal</h3>
      <p>Ritika played a key role in the platform’s design and development. <br><br>Her expertise in web development and user experience ensures that the platform is both functional and visually appealing.</p>
    </div>
  </div>
</section>

  </div>

  <?php include '../layout/footer.htm' ?>
</body>

</html>