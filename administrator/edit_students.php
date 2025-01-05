<?php
include "../backend/db_connection.php"; 

// Fetch the student details for editing
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']); // Sanitize input

    // Fetch student data
    $sql = "SELECT * FROM student WHERE std_id = :std_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':std_id' => $student_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $name = $row['name'];
        $email = $row['email'];
        $semester = $row['semester'];
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "Student ID is missing from the URL.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $semester = intval($_POST['semester']);

    // Update the student record
    $update_sql = "UPDATE student SET name = :name, email = :email, semester = :semester WHERE std_id = :std_id";
    $stmt = $pdo->prepare($update_sql);

    if ($stmt->execute([':name' => $name, ':email' => $email, ':semester' => $semester, ':std_id' => $student_id])) {
        echo "<script>
            alert('Student updated successfully!');
            window.location.href = 'student_list.php';
        </script>";
        exit;
    } else {
        echo "Error updating student information.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="/assets/css/edit_st.css">
</head>
<body>
  <div class="edit-container">
    <div class="header">
      <h1>Edit Student Information</h1>
      <?php if (isset($student_id)) { ?>
                <div class="std_id">Student ID: <?= $student_id ?></div>
            <?php } ?>
    </div>

    <div class="form-container">
      <a href="student_list.php" class="close-btn">&times;</a>

      <form method="POST" action="./edit_students.php?id=<?= $student_id ?>">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required><br>
    
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br>
    
          <label for="semester">Semester:</label>
          <input type="text" id="semester" name="semester" value="<?= htmlspecialchars($semester) ?>" required><br>
    
          <input type="submit" value="Update Student">
        </form>
      </div>
    </div>


</body>
</html>
