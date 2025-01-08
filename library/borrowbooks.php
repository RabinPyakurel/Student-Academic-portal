<?php
session_start(); // Ensure the session is started

// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read JSON data from the request body
$input = json_decode(file_get_contents('php://input'), true);

// Validate and sanitize inputs
$book_id = isset($input['book_id']) ? intval($input['book_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Use the correct variable

$borrow_date = date('Y-m-d H:i:s');

// Calculate return date as 15 days from the borrow date
$return_date = date('Y-m-d H:i:s', strtotime('+15 days', strtotime($borrow_date)));

// Check if all inputs are provided
if ($book_id <= 0 || $user_id <= 0 || empty($borrow_date) || empty($return_date)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    $conn->close();
    exit();
}

// Start a transaction to ensure data consistency
$conn->begin_transaction();

try {
    // Update the available copies in the library table
    $update_sql = "UPDATE library SET available_copies = available_copies - 1 WHERE book_id = ? AND available_copies > 0";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Error: Book is not available.");
    }

    // Insert a record into the borrowedbooks table
    $insert_sql = "INSERT INTO borrowedbooks (std_id, book_id, borrowed_date, returned_date) 
                   VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $return_date);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    echo json_encode(["status" => "success", "message" => "Book borrowed successfully!"]);
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
