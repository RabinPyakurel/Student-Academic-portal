<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get category from query string
$category = isset($_GET['category']) ? $_GET['category'] : '';

// SQL query for a single category
$sql = "SELECT title, author,book_id, category, book_image FROM books";
if ($category) {
    $sql .= " WHERE category = ?";
}

$stmt = $conn->prepare($sql);

if ($category) {
    $stmt->bind_param("s", $category);
}

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Query execution failed: " . $stmt->error]);
    exit();
}

$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "data" => $books,
]);



?>