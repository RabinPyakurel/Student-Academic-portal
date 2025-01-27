<?php
include "../backend/db_connection.php"; 

// Check if book_id is set, if not, return an error
if (!isset($_GET['book_id'])) {
    echo "Error: Book ID is missing.";
    exit;
}

$book_id = $_GET['book_id'];

// Fetch current book details
$sql = "SELECT * FROM books WHERE book_id = :book_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':book_id' => $book_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Error: Book not found.";
    exit;
}

// Default values for the form fields
$title = $row['title'];
$author = $row['author'];
$publisher = $row['publisher'];
$published_year = $row['year_published'];
$available_copies = $row['available_copies'];
$total_copies = $row['total_copies'];
$category = $row['category'];
$book_image = $row['book_image'];

// Form handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $published_year = trim($_POST['year_published']);
    $available_copies = trim($_POST['available_copies']);
    $total_copies = trim($_POST['total_copies']);
    $category = trim($_POST['category']);
    $book_image = $row['book_image']; // Keep original image by default

    // Handle image upload
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['book_image']['tmp_name'];
        $imageFileType = strtolower(pathinfo($_FILES['book_image']['name'], PATHINFO_EXTENSION));

        $uploadDir = __DIR__ . '/../assets/images/BooksUpload/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = $book_id . '.' . $imageFileType;
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($imageTmpName, $uploadPath)) {
            $book_image = '/assets/images/BooksUpload/' . $newFileName;
        } else {
            echo "Error moving uploaded file.";
            exit;
        }
    } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] == 1) {
        $book_image = null;
    }

    // Update book record
    $update_sql = "UPDATE books 
                   SET title = :title, 
                       author = :author, 
                       publisher = :publisher, 
                       year_published = :year_published, 
                       available_copies = :available_copies, 
                       total_copies = :total_copies, 
                       book_image = :book_image, 
                       category = :category 
                   WHERE book_id = :book_id";

    $stmt = $pdo->prepare($update_sql);

    if ($stmt->execute([
        ':title' => $title, 
        ':author' => $author, 
        ':publisher' => $publisher, 
        ':year_published' => $published_year, 
        ':available_copies' => $available_copies, 
        ':total_copies' => $total_copies, 
        ':book_image' => $book_image, 
        ':category' => $category, 
        ':book_id' => $book_id
    ])) {
        echo "<script>
            alert('Book updated successfully!');
            window.location.href = 'manage_books.php';
        </script>";
        exit;
    } else {
        echo "Error updating book information.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Book</title>
  <link rel="stylesheet" href="/assets/css/edit_st.css">
  <link rel="stylesheet" href="./editbook.css">
  <style>
  /* Styling for the form */
  /* Add styles for the form here as needed */
  </style>
</head>

<body>
  <div class="edit-container">
    <div class="header">
      <h1>Edit Book Information</h1>
      <div class="std_id">Book ID: <?= $book_id ?></div>
    </div>

    <div class="form-container">
      <a href="manage_books.php" class="close-btn">&times;</a>

      <form method="POST" action="edit_book.php?book_id=<?= $book_id ?>" enctype="multipart/form-data"
        onsubmit="return validateForm()">

        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required><br>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($author) ?>" required><br>

        <label for="publisher">Publisher:</label>
        <input type="text" id="publisher" name="publisher" value="<?= htmlspecialchars($publisher) ?>" required><br>

        <label for="year_published">Year Published:</label>
        <input type="number" id="year_published" name="year_published" value="<?= htmlspecialchars($published_year) ?>"
          required><br>

        <label for="available_copies">Available Copies:</label>
        <input type="number" id="available_copies" name="available_copies"
          value="<?= htmlspecialchars($available_copies) ?>" required><br>

        <label for="total_copies">Total Copies:</label>
        <input type="number" id="total_copies" name="total_copies" value="<?= htmlspecialchars($total_copies) ?>"
          required><br>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($category) ?>" required><br>

        <label for="book_image">Book Image:</label><br>
        <!-- Display existing image if available -->
        <div id="event_image_section">
          <img src="<?= isset($book_image) ? $book_image : 'default_image_path.jpg'; ?>" alt="Book Image"
            id="existing_book_image" style="max-width: 150px; height: auto;">
          <br>
        </div>
        <!-- Input for new image -->

        <input type="checkbox" id="remove_image" name="remove_image" value="1">
        <label for="remove_image" id="rem_img">Remove existing image</label><br><br>
        <input type="file" id="book_image" name="book_image"><br><br>
        <?php
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == 1) {
          // Logic to remove the image from the database and the file system
          $remove_image_query = "UPDATE books SET book_image = NULL WHERE book_id = $book_id";
          mysqli_query($conn, $remove_image_query);
          
          // Optional: Delete the image file from the server if you need to remove it physically
          $existing_image_path = 'path_to_existing_image'; // Get this path from your DB record
          if (file_exists($existing_image_path)) {
              unlink($existing_image_path); // Delete the file
          }
      } elseif (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
          // Logic to upload the new image
          $image_path = 'uploads/' . basename($_FILES['book_image']['name']);
          move_uploaded_file($_FILES['book_image']['tmp_name'], $image_path);
          
          // Update the database with the new image path
          $update_image_query = "UPDATE books SET book_image = '$image_path' WHERE book_id = $book_id";
          mysqli_query($conn, $update_image_query);
      }
      
        ?>
        <input type="submit" value="Update Book">
      </form>
    </div>
  </div>

  <script src="editbook.js"></script>
</body>

</html>