<?php
include "./db_connection.php"; 



// Check if the student ID is passed via the URL (GET method)
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    

    // Fetch the student data from the database
    $sql = "SELECT * FROM student WHERE std_id = ?";
    if ($stmt = $connection->prepare($sql)) {
        $stmt->bind_param("i", $student_id);  // 'i' for integer parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $email = $row['email'];
            $semester = $row['semester'];
        } else {
            echo "Student not found.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Error preparing the query.";
        exit;
    }
} else {
    echo "Student ID is missing from the URL.";
    exit;
}
?>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $semester = $_POST['semester'];

    // Update the student information
    $update_sql = "UPDATE student SET name = ?, email = ?, semester = ? WHERE std_id = ?";
    if ($stmt = $connection->prepare($update_sql)) {
        $stmt->bind_param("sssi", $name, $email, $semester, $student_id);
        if ($stmt->execute()) {
          echo "<script>
          alert('Student updated successfully!');
          window.location.href = 'student_list.php';  // Redirect to the student list page
        </script>";
            exit;
        } else {
            echo "Error updating student!";
        }
        $stmt->close();
    } else {
        echo "Error preparing the query.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="./edit_st.css">
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
