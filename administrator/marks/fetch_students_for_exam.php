<?php
include __DIR__ . '/../../backend/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_id = $_POST['exam_id'];

    // Fetch the course_id and program_id associated with the exam
    $query_exam = "SELECT course_id, program_id, semester FROM exam WHERE exam_id = :exam_id";
    $stmt_exam = $pdo->prepare($query_exam);
    $stmt_exam->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
    $stmt_exam->execute();
    $exam = $stmt_exam->fetch(PDO::FETCH_ASSOC);

    // Fetch students for the exam's program and semester
    $query_students = "SELECT std_id, name FROM student WHERE program_id = :program_id AND semester = :semester";
    $stmt_students = $pdo->prepare($query_students);
    $stmt_students->bindParam(':program_id', $exam['program_id'], PDO::PARAM_INT);
    $stmt_students->bindParam(':semester', $exam['semester'], PDO::PARAM_STR);
    $stmt_students->execute();
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for students and marks input
    if ($students) {
        echo '<table><thead><tr><th>Student Name</th><th>Marks Obtained</th></tr></thead><tbody>';
        foreach ($students as $student) {
            echo '<tr>
                    <td>' . htmlspecialchars($student['name']) . '</td>
                    <td><input type="number" name="marks_data[' . $student['std_id'] . ']" step="0.01" min="0" max="100" required></td>
                  </tr>';
        }
        echo '</tbody></table>';
        echo '<input type="hidden" name="course_id" value="' . htmlspecialchars($exam['course_id']) . '">';
    } else {
        echo '<p>No students found for the selected exam.</p>';
    }
}
?>
