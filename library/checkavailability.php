<?php
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

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

// Fetch book details for `GET` request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($book_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid book ID."]);
        exit();
    }

    $sql = "SELECT title, book_id, author, available_copies FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo json_encode(["status" => "error", "message" => "Book not found."]);
        exit();
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reserve book for `POST` request
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

    if ($book_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid book ID."]);
        exit();
    }

    // Check book availability
    $sql = "SELECT available_copies FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        if ($book['available_copies'] > 0) {
            // Reserve the book
            $update_sql = "UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?";
           
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
            echo json_encode(["status" => "success", "message" => "Book reserved  successfully!"]);
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
    } else {
        echo json_encode(["status" => "error", "message" => "Book not found."]);
    }
    $stmt->close();

    
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Availability</title>
  <link rel="stylesheet" href="./available.css">
</head>

<body>
  <div class="book-card">
    <h3>Book Title: <?php echo htmlspecialchars($book['title'] ?? ''); ?></h3>
    <p>Author: <?php echo htmlspecialchars($book['author'] ?? ''); ?></p>
    <p>Book ID: <?php echo htmlspecialchars($book['book_id'] ?? ''); ?></p>
    <p>Availability:
      <span id="availability-status"
        class="<?php echo $book['available_copies'] > 0 ? 'available' : 'not-available'; ?>">
        <?php echo $book['available_copies'] > 0 ? 'Available' : 'Not Available'; ?>
      </span>
    </p>


    <button id="reserve-button" class="borrow-button" data-book-id="<?php echo $book_id; ?>"
      <?php echo $book['available_copies'] > 0 ? '' : 'disabled'; ?>>
      Reserve
    </button>



    <p id="availability-message">
      <?php echo $book['available_copies'] > 0 ? '' : 'Please wait for some more days.'; ?>
    </p>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const reserveButton = document.getElementById('reserve-button');

    reserveButton.addEventListener('click', function() {
      const bookId = reserveButton.dataset.bookId;

      fetch('checkavailability.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: `book_id=${bookId}`,
        })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 'success') {
            alert(data.message);
            location.reload(); // Reload to update availability
          } else {
            alert(data.message);
          }
        })
        .catch((error) => console.error('Error:', error));
    });
  });
  </script>
</body>

</html>