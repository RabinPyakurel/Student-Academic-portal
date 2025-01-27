<?php

$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Book deleted successfully.');
            window.history.back(); 
        </script>";
    } else {
        echo "
        <script>
            alert('Error deleting book: " . htmlspecialchars($stmt->error) . "');
            window.history.back(); 
        </script>";
    }

    $stmt->close();
} else {
    echo "No book ID specified.";
}

$conn->close();
?>