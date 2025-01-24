<?php
// session_start();
// if (!isset($_SESSION['admin_logged_in'])) {
//     header('Location: login.php');
//     exit();
//}

// Database connection
$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch books from the database
$sql = "SELECT * FROM library";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="add_book.php">Add New Book</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Year Published</th>
                <th>Available Copies</th>
                <th>Total Copies</th>
                <th>Category</th>
                <!-- <th>Image</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                    <td><?php echo htmlspecialchars($row['year_published']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_copies']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_copies']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <!-- <td><img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Book Image" width="50"></td> -->
                    <td>
                        <a href="edit_book.php?id=<?php echo $row['book_id']; ?>">Edit</a>
                        <a href="delete_book.php?id=<?php echo $row['book_id']; ?>" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
