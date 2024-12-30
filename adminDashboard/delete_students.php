<?php
include "db_connection.php"; 


if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    if (is_numeric($student_id)) {
        
        $sql = "DELETE FROM student WHERE std_id = ?";
        
        if ($stmt = $connection->prepare($sql)) {
            $stmt->bind_param("i", $student_id); // 
            if ($stmt->execute()) {
                echo "<script>alert('Student deleted successfully!'); window.location.href='student_list.php';</script>";
                exit;
            } else {
              echo "<script>alert('Error deleting student!'); window.location.href='student_list.php';</script>";
            }
            $stmt->close();
        } else {
            echo "Error preparing the query.";
        }
    } else {
        echo "Invalid student ID.";
    }
} else {
    echo "Student ID is missing from the URL.";
}
?>
<script>
  document.querySelectorAll('.delete-link').forEach(link => {
  link.addEventListener('click', function (e) {
    if (!confirm('Are you sure you want to delete this student?')) {
      e.preventDefault();
    }
  });
});

</script>
