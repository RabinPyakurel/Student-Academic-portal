<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        // Get form data
        $title = $_POST['title'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $year_published = $_POST['year_published'];
        $available_copies = $_POST['available_copies'];
        $total_copies = $_POST['total_copies'];
        $category = $_POST['category'];

        // Image handling
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $baseDirectory = __DIR__ . '/../assets/images/';
        $uploadDir = $baseDirectory . '/BooksUpload/';
        $publicDir = '/assets/images/BooksUpload/';
        $publicPath = $publicDir . $imageName;

        // Move image to the correct directory
        if (move_uploaded_file($imageTmpName, $uploadDir . $imageName)) {

            // Retrieve next available book ID
            $result = $conn->query("SELECT MAX(book_id) AS max_id FROM books");
            $row = $result->fetch_assoc();
            $book_id = $row['max_id'] + 1; 

            // Prepare SQL query to insert book data along with image path and manually set book_id
            $stmt = $conn->prepare("INSERT INTO books (book_id, title, author, publisher, year_published, available_copies, total_copies, book_image, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssiisss", $book_id, $title, $author, $publisher, $year_published, $available_copies, $total_copies, $publicPath, $category);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Book inserted successfully!')</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "No image uploaded or upload error.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Books</title>
  <link rel="stylesheet" href="../assets/css/addbooks.css">
</head>

<body>
  <?php include "./sidebar.htm"; ?>

  <div class="addbooks">
    <form method="POST" enctype="multipart/form-data" action="addbooks.php" id="addBookForm">
      <h2>Add Books</h2>

      <label for="title">Title:</label>
      <input type="text" name="title" id="title" required>
      <span id="titleError" class="error-message"></span><br><br>

      <label for="author">Author:</label>
      <input type="text" name="author" id="author" required>
      <span id="authorError" class="error-message"></span><br><br>

      <label for="publisher">Publisher:</label>
      <input type="text" name="publisher" id="publisher" required>
      <span id="publisherError" class="error-message"></span><br><br>

      <label for="year_published">Year Published:</label>
      <input type="date" name="year_published" id="year_published" required>
      <span id="yearError" class="error-message"></span><br><br>

      <label for="available_copies">Available Copies:</label>
      <input type="number" name="available_copies" id="available_copies" required>
      <span id="availableCopiesError" class="error-message"></span><br><br>

      <label for="total_copies">Total Copies:</label>
      <input type="number" name="total_copies" id="total_copies" required>
      <span id="totalCopiesError" class="error-message"></span><br><br>

      <label for="category">Category:</label>
      <select name="category" id="category" required>
        <option value="Fiction">Fiction</option>
        <option value="Text Book">Text Book</option>
        <option value="Technology">Technology</option>
        <option value="Self-Help">Self-Help</option>
        <option value="biography">Biography</option>
        <option value="mystery">Mystery</option>
      </select><br><br>

      <label for="image">Upload Image:</label>
      <input type="file" name="image" id="image" required>
      <span id="imageError" class="error-message"></span><br><br>

      <input type="submit" value="Submit">
    </form>
  </div>

  <script>
  document.getElementById('addBookForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission to perform validation first
    let isValid = true; // Initialize the validation flag

    document.querySelectorAll('.error-message').forEach(error => {
      error.style.display = 'none'; // Hide error messages before checking
    });

    // Title validation (non-empty, alphanumeric)
    const title = document.getElementById('title');
    const titlePattern = /^[a-zA-Z0-9:\s]+$/;
    if (!titlePattern.test(title.value)) {
      document.getElementById('titleError').textContent = 'Invalid title. Only alphanumeric characters allowed.';
      document.getElementById('titleError').style.display = 'block';
      isValid = false;
    }

    // Other validations...

    // Image file validation (only image files)
    const image = document.getElementById('image');
    const imagePattern = /\.(jpg|jpeg|png|gif)$/i; // Only image formats
    if (!imagePattern.test(image.value)) {
      document.getElementById('imageError').textContent = 'Please upload a valid image file (jpg, jpeg, png, gif).';
      document.getElementById('imageError').style.display = 'block';
      isValid = false;
    }

    // If everything is valid, submit the form
    if (isValid) {
      this.submit();
    }
  });
  </script>

</body>

</html>