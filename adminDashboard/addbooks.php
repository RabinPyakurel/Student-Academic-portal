
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Books</title>
</head>
<style>
 
/* General Form Styling */
form {
    width: 60%;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Form Labels */
label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

/* Input Fields */
input[type="text"],
input[type="number"],
input[type="file"],
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
}

/* Input Focus */
input[type="text"]:focus,
input[type="number"]:focus,
input[type="file"]:focus,
select:focus {
    border-color: #007bff;
    outline: none;
}

/* Submit Button */
input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

/* Submit Button Hover */
input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        width: 90%;
    }
}

</style>
<body>
  <form method="POST" enctype="multipart/form-data" action="addbooks.php">
    <h2> Add Books</h2>
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required><br><br>

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" required><br><br>

    <label for="publisher">Publisher:</label>
    <input type="text" name="publisher" id="publisher" required><br><br>

    <label for="year_published">Year Published:</label>
    <input type="number" name="year_published" id="year_published" required><br><br>

    <label for="available_copies">Available Copies:</label>
    <input type="number" name="available_copies" id="available_copies" required><br><br>

    <label for="total_copies">Total Copies:</label>
    <input type="number" name="total_copies" id="total_copies" required><br><br>

    <label for="category">Category:</label>
    <select name="category" id="category" required>
        <option value="fiction">Fiction</option>
        <option value="technology">Technology</option>
        <option value="history">History</option>
        <option value="science">Science</option>
        <option value="biography">Biography</option>
        <option value="mystery">Mystery</option>
    </select><br><br>

    <label for="image">Upload Image:</label>
    <input type="file" name="image" id="image" required><br><br>

    <input type="submit" value="Submit">
</form>


</body>
</html>
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
      
        $title = $_POST['title'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $year_published = $_POST['year_published'];
        $available_copies = $_POST['available_copies'];
        $total_copies = $_POST['total_copies'];
        $category = $_POST['category'];

    
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $baseDirectory = __DIR__ . '/../assets/images/';
        $uploadDir = $baseDirectory . '/BooksUpload/';
        $publicDir = '/assets/images/BooksUpload/';
        $publicPath = $publicDir . $imageName;
        if (move_uploaded_file($imageTmpName, $uploadDir . $imageName)) {
            echo "Image uploaded successfully!<br>";

            
            $result = $conn->query("SELECT MAX(book_id) AS max_id FROM library");
            $row = $result->fetch_assoc();
            $book_id = $row['max_id'] + 1; 

            // Prepare SQL query to insert book data along with the image URL and manually set book_id
            $stmt = $conn->prepare("INSERT INTO library (book_id, title, author, publisher, year_published, available_copies, total_copies, book_image, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssiisss", $book_id, $title, $author, $publisher, $year_published, $available_copies, $total_copies, $publicPath, $category);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Book inserted successfully!";
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
