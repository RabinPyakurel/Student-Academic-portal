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

$book_id = $_POST['book_id'];
$std_id = $_POST['std_id'];
$borrow_date = $_POST['borrow_date'];
$return_date = $_POST['return_date'];

// Update the available copies in the library table
$sql = "UPDATE library SET available_copies = available_copies - 1 WHERE book_id = $book_id";
if ($conn->query($sql) === TRUE) {
    // Insert record in borrowedbooks table
    $insert_sql = "INSERT INTO borrowedbooks (std_id, book_id, borrowed_date, returned_date) 
                   VALUES ($std_id, $book_id, '$borrow_date', '$return_date')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "Book borrowed successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
