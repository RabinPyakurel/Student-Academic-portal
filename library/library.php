<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library</title>
  <link rel="stylesheet" href="./library.css">
 

  <link href='https://fonts.googleapis.com/css?family=Alegreya SC' rel='stylesheet'>

</head>

<body>
  <?php
  include "../layout/nav.htm";
  ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>डिजिटल उपन्यासहरूको संसार</h1>
      <p>The World of Novels: Now in Your Library</p>
      <div class="search-bar">
        <input type="text" placeholder="Search for books, authors, or categories...">
        <button>Search</button>
      </div>
    </div>
  </section>

  <!-- Featured Books -->
  <section id="featured" class="featured">
    <div class="container">
      <h2>Featured Books</h2>
      <div class="book-grid">
        <div class="book-card">
          <img src="../assets/images/images (1).jpeg" alt="Book Cover">
          <h3>Life in woods</h3>
          <p>Author : Henry David Thoreau</p>
          <button class="action-button">Borrow</button>
          <button class="action-button">Check Availability</button>
        </div>
        <div class="book-card">
          <img src="../assets/images/2ndlib.jpeg" alt="Book Cover">
          <h3>Eklo</h3>
          <p>Author : Buddhi Sagar</p>
          <button class="action-button">Borrow</button>
          <button class="action-button">Check Availability</button>
        </div>
        <div class="book-card">
          <img src="../assets/images/3rdlib.jpeg" alt="Book Cover">
          <h3>Mochan</h3>
          <p>Author : Tulasi Acharya</p>
          <button class="action-button">Borrow</button>
          <button class="action-button">Check Availability</button>
        </div>
        <div class="book-card">
          <img src="../assets/images/4thlib.jpeg" alt="Book Cover">
          <h3>Ijoriya</h3>
          <p>Author : Subin Bhattarai</p>
          <button class="action-button">Borrow</button>
          <button class="action-button">Check Availability</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Categories -->
  <section id="categories" class="categories">
    <div class="container">
      <h2>Categories</h2>
      <div class="category-grid">
        <div class="category-card">Fiction</div>
        <div class="category-card">Science</div>
        <div class="category-card">History</div>
        <div class="category-card">Technology</div>
        <div class="category-card">Biography</div>
        <div class="category-card">Mystery</div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php
  
  include "../layout/footer.htm";
  ?>
 
  <script>
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    menuToggle.addEventListener('click', () => {
      navLinks.classList.toggle('show');
    });

  </script>
</body>

</html>