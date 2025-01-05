<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get category from query string
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Query to fetch books by category
$sql = "SELECT title, author, category, book_image FROM library";
if ($category) {
    $sql .= " WHERE category = '$category'"; // Filter by category
}

$result = $conn->query($sql);

// Fetch the books data into an array
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Close the connection
$conn->close();

// Set header for JSON response
header('Content-Type: application/json');

// Return books as JSON
echo json_encode($books);
?>
