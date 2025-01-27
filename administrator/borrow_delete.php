<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$dbname = "sapo";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if borrow_id is set
if (isset($_GET['borrow_id'])) {
    $borrow_id = $_GET['borrow_id'];

    // Delete the record from the borrowedbooks table
    $sql = "DELETE FROM borrowedbooks WHERE borrow_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $borrow_id);

    if ($stmt->execute()) {
        // Redirect with a success message using query parameter
        header("Location: borrowedbooks.php?message=Record%20deleted%20successfully");
        exit();
    } else {
        // Redirect with an error message using query parameter
        header("Location: borrowedbooks.php?message=Error:%20Unable%20to%20delete%20record");
        exit();
    }

} else {
    echo "Error: No borrow_id provided.";
}

$conn->close();
?>