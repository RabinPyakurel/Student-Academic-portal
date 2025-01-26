<?php
$conn = new PDO("mysql:host=localhost;dbname=sapo", "root", "rabin");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form input values
    $student_id = $_POST['student_id'];
    $semester = $_POST['semester']; // Get course ID
    $date = $_POST['date'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'] ?? null; // Handle optional remarks

    try {
        // Prepare SQL query to insert attendance
        $query = "INSERT INTO attendance (std_id, semester, date, status, remarks)
                  VALUES (:student_id, :semester, :date, :status, :remarks)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':student_id' => $student_id,
            ':semester' => $semester,
            ':date' => $date,
            ':status' => $status,
            ':remarks' => $remarks
        ]);

        echo "<script>alert('Attendance added successfully!');</script>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
