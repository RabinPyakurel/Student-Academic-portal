
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    include 'not-found.htm';
    exit();
}
?>
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for Featured Books
$sql = "SELECT book_id, title, author, category, book_image FROM books ORDER BY book_id DESC LIMIT 4";

$result = $conn->query($sql);

$featuredBooks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredBooks[] = $row;
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library</title>
  <link rel="stylesheet" href="./library.css">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body>
  <?php include "../layout/nav.htm"; ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Library</h1>
      <p>Endless Access to Knowledge from the Comfort of Your Home</p>
      <div class="search-bar">
        <input type="text" placeholder="Search for books, authors, or categories...">
        <button>Search</button>
      </div>
    </div>
  </section>

  <!-- Featured Books Section -->
  <section id="featured" class="featured">
    <div class="container">
      <h2>Featured Books</h2>
      <div class="book-grid" id="featured-grid">
        <?php
        foreach ($featuredBooks as $book) {
          echo '<div class="book-card">';
          echo '<img class="book-img" src="' . $book['book_image'] . '" alt="' . $book['title'] . '">';
          echo '<h3>' . $book['title'] . '</h3>';
          echo '<p>Author: ' . $book['author'] . '</p>';
          echo '<p>Category: ' . $book['category'] . '</p>';
          echo '<a href="checkavailability.php?book_id=' . $book['book_id'] . '"><button class="action-button">Check Availability</button></a>';
          echo '</div>';
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section id="categories" class="categories">
    <div class="container">
      <h2>Categories</h2>
      <div class="category-grid">
        <div class="category-card" onclick="filterBooks('Fiction')">Fiction</div>
        <div class="category-card" onclick="filterBooks('Self-Help')">Self-Help</div>
        <div class="category-card" onclick="filterBooks('Text Book')">Text Book</div>
        <div class="category-card" onclick="filterBooks('Technology')">Technology</div>
        <div class="category-card" onclick="filterBooks('Biography')">Biography</div>
        <div class="category-card" onclick="filterBooks('Mystery')">Mystery</div>
      </div>
    </div>
    <div id="category-books" class="category-books">
      <!-- Books from selected category will be displayed here -->
    </div>
  </section>
  
  
  <!-- <h2>Books We Love</h2>
  <div id="book-container" class="book-container"></div>
 -->

  
  <?php include "../layout/footer.htm"; ?>

  <script src="./library.js"></script>
</body>

</html>
