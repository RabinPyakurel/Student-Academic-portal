<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    include 'not-found.htm';
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Featured Books
$featuredBooks = [];
$featuredQuery = "SELECT book_id, title, author, category, book_image FROM books ORDER BY book_id DESC LIMIT 4";
$featuredResult = $conn->query($featuredQuery);

if ($featuredResult->num_rows > 0) {
    while ($row = $featuredResult->fetch_assoc()) {
        $featuredBooks[] = $row;
    }
}

// Search and Filter
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM books WHERE 1";

$params = [];
if ($filter !== 'all') {
    $query .= " AND author = ?";
    $params[] = $filter;
}
if (!empty($search)) {
    $query .= " AND (title LIKE ? OR author LIKE ? OR category LIKE ?)";
    $searchTerm = "%$search%";
    array_push($params, $searchTerm, $searchTerm, $searchTerm);
}

// Prepare statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$searchResults = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

$stmt->close();
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
        <form method="GET" action="">
          <input type="text" name="search" placeholder="Search for books, authors, or categories..."
            value="<?php echo htmlspecialchars($search); ?>">
          <button type="submit">Search</button>
        </form>
      </div>
    </div>
  </section>

  <!-- Search Results Section -->
  <?php if (!empty($search)): ?>
  <section id="search-results" class="search-results">
    <div class="container">
      <h2>Search Results for "<?php echo htmlspecialchars($search); ?>"</h2>
      <div class="book-grid">
        <?php if (count($searchResults) > 0): ?>
        <?php foreach ($searchResults as $book): ?>
        <div class="book-card">
          <img class="book-img" src="<?php echo $book['book_image']; ?>"
            alt="<?php echo htmlspecialchars($book['title']); ?>">
          <h3><?php echo htmlspecialchars($book['title']); ?></h3>
          <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
          <p>Category: <?php echo htmlspecialchars($book['category']); ?></p>
          <a href="checkavailability.php?book_id=<?php echo $book['book_id']; ?>">
            <button class="action-button">Check Availability</button>
          </a>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p>No books found matching your search.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Featured Books Section -->
  <section id="featured" class="featured">
    <div class="container">
      <h2>Featured Books</h2>
      <div class="book-grid" id="featured-grid">
        <?php foreach ($featuredBooks as $book): ?>
        <div class="book-card">
          <img class="book-img" src="<?php echo $book['book_image']; ?>" alt="<?php echo $book['title']; ?>">
          <h3><?php echo $book['title']; ?></h3>
          <p>Author: <?php echo $book['author']; ?></p>
          <p>Category: <?php echo $book['category']; ?></p>
          <a href="checkavailability.php?book_id=<?php echo $book['book_id']; ?>">
            <button class="action-button">Check Availability</button>
          </a>
        </div>
        <?php endforeach; ?>
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

  <?php include "../layout/footer.htm"; ?>

  <script src="./library.js"></script>
</body>

</html>