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

$sql = "SELECT 
            bb.borrow_id,
            s.name,
            s.std_id,
            s.email, 
            b.title,
            b.book_id,
            bb.borrowed_date,
            bb.returned_date
        FROM borrowedbooks bb
        JOIN student s ON bb.std_id = s.std_id
        JOIN books b ON bb.book_id = b.book_id";

$result = $conn->query($sql);

// Group data by student name
$studentData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studentData[$row['name']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Borrowed Books</title>
  <link rel="stylesheet" href="borrowbooks.css">
  <style>

  </style>
</head>

<body>
  <?php
  include "sidebar.htm";
  ?>
  <div class="container">
    <h1>Borrowed Books by Students</h1>

    <?php if (isset($_GET['message'])): ?>
    <p class="message">
      <?php echo htmlspecialchars($_GET['message']); ?>
    </p>
    <?php endif; ?>

    <!-- Rest of your table and content code goes here -->

    <?php if (!empty($studentData)): ?>
    <?php foreach ($studentData as $studentName => $books): ?>
    <table>
      <thead>
        <tr>
          <th colspan="5" class="student-name">Student: <?= htmlspecialchars($studentName) ?></th>
        </tr>
        <tr>
          <th>Book Title</th>
          <th>Book ID</th>
          <th>Borrow Date</th>
          <th>Return Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($books as $book): ?>
        <tr>
          <td><?= htmlspecialchars($book['title']) ?></td>
          <td><?= htmlspecialchars($book['book_id']) ?></td>
          <td><?= htmlspecialchars($book['borrowed_date']) ?></td>
          <td><?= htmlspecialchars($book['returned_date'] ?? 'Not Returned') ?></td>
          <td>

            <!-- Delete Record Button -->
            <a href="borrow_delete.php?borrow_id=<?= $book['borrow_id'] ?>" class="action-btn delete-btn"
              onclick="return confirm('Are you sure you want to delete this record? This action cannot be undone.')">Delete
              Record</a>
          </td>



        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endforeach; ?>
    <?php else: ?>
    <p>No borrowed books found.</p>
    <?php endif; ?>
  </div>
  <script>
  const urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get('message');

  if (message) {
    alert(message);
    const url = new URL(window.location.href);
    url.searchParams.delete('message');
    window.history.replaceState({}, '', url.toString());
  }
  </script>
</body>

</html>

<?php
$conn->close();
?>