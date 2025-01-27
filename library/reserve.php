<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$std_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if (!isset($_POST['book_id']) || !is_numeric($_POST['book_id'])) {
    error_log("Received GET data: " . json_encode($_GET));  // Log the GET parameters
    echo json_encode(["status" => "error", "message" => "Invalid or missing book ID in GET parameter."]);
    exit();
}

$book_id = intval($_POST['book_id']); // Ensure it's an integer
error_log("Received book_id: " . $book_id);  // Log the book_id


// Debugging: Log incoming data
error_log("Received book_id: $book_id from user_id: $std_id");

// Check available copies
$sql = "SELECT available_copies FROM library WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($book['available_copies'] > 0) {
    // Proceed with reserving the book
    $update_sql = "UPDATE library SET available_copies = available_copies - 1 WHERE book_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $book_id);
    
    if ($update_stmt->execute()) {
        // Now insert the record into the borrowedbooks table
        $borrow_date = date("Y-m-d"); // Current date
        $return_date = date("Y-m-d", strtotime("+14 days")); // 14 days later

        $insert_sql = "INSERT INTO borrowedbooks (std_id, book_id, borrowed_date, returned_date) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiss", $std_id, $book_id, $borrow_date, $return_date);
        
        if ($insert_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Book reserved and borrowed successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert into borrowedbooks table."]);
        }

        $insert_stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to reserve the book."]);
    }

    $update_stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No available copies."]);
}

$conn->close();
?>