<?php
include "./db_connection.php";

// Handle filtering
$filter = $_GET['filter'] ?? 'all'; // Default to 'all' if no filter is selected
$search = $_GET['search'] ?? ''; // Get search input

$query = "SELECT * FROM books WHERE 1";
if ($filter !== 'all') {
    $query .= " AND author = '$filter'";
}
if (!empty($search)) {
    $query .= " AND title LIKE '%$search%'";
}

$query .= " ORDER BY book_id DESC";

$result = $connection->query($query);



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book List</title>
  <link rel="stylesheet" href="/assets/css/student_list.css">
  <style>
  main {
    color: black;
  }

  .table-container table {
    width: 100%;
    border-collapse: collapse;
  }

  .table-container th,
  .table-container td {
    border: 1px solid #ddd;
    padding: 8px;
  }

  .table-container th {
    background-color: #f4f4f4;
    text-align: left;
  }

  .table-container td img {
    max-width: 100px;
    height: auto;
    display: block;
  }

  .actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .edit-link,
  .delete-link {
    text-decoration: none;
    color: blue;
    cursor: pointer;
  }

  .delete-link {
    color: red;
  }

  #book_d {
    width: 40%;
  }

  .btn-borrow {
    background-color: white;
    padding: 0.3rem;
    border: 2px solid;
    border-radius: 0.5rem;
    margin-left: 2vw;
    font-size: 0.8rem;
  }
  </style>
</head>

<body>
  <?php include './sidebar.htm'; ?>
  <main>
    <h1>Book List</h1>
    <a href="borrowedbooks.php" class="btn-borrow">Borrowed List>></a>
    <div class="filter-container">
      <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by Book Name..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th id="book_d">Book Details</th>
            <th>Book Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <!-- Book Details -->
            <td>
              <strong>Title:</strong> <?= $row['title'] ?><br>
              <strong>Author:</strong> <?= $row['author'] ?><br>
              <strong>Category:</strong> <?= $row['category'] ?><br>
              <strong>Publisher:</strong> <?= $row['publisher'] ?><br>
              <strong>Year Published:</strong> <?= $row['year_published'] ?><br>
              <strong>Available Copies:</strong> <?= $row['available_copies'] ?><br>
              <strong>Total Copies:</strong> <?= $row['total_copies'] ?><br>
            </td>

            <!-- Book Image -->
            <td>
              <?php if (!empty($row['book_image'])): ?>
              <img src="<?= htmlspecialchars($row['book_image']) ?>" alt="Book Image">
              <?php else: ?>
              <p>No Image Available</p>
              <?php endif; ?>
            </td>

            <!-- Actions -->
            <td class="actions">
              <a class="edit-link" href="./edit_books.php?book_id=<?= $row['book_id'] ?>">Edit</a>
              <a class="delete-link" href="./delete_book.php?book_id=<?= $row['book_id'] ?>"
                onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php else: ?>
          <tr>
            <td colspan="3">No Book Record found.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>

</html>