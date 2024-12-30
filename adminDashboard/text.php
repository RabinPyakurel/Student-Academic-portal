<?php
include "db_connection.php"; // Ensure the database connection is included

// Check if student_id is passed in the query string
if (isset($_GET['std_id']) && is_numeric($_GET['std_id'])) {
    $student_id = $_GET['std_id'];

    // Fetch the student data from the database
    $sql = "SELECT std_id, name, email, semester FROM students WHERE std_id = ?";
    if ($stmt = $conn->prepare($sql)) {
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
    echo "Invalid student ID!";
    exit;
}
?>

<!-- HTML Form to display student details -->
<form method="post" action="update_student.php">
    <input type="hidden" name="std_id" value="<?php echo $student_id; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

    <label for="semester">Semester:</label>
    <input type="text" id="semester" name="semester" value="<?php echo htmlspecialchars($semester); ?>" required><br>

    <input type="submit" value="Update">
</form>
