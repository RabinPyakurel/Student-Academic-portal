<?php
include __DIR__ .'/../../backend/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'];
    $semester = $_POST['semester'];
    $exam_type = $_POST['exam_type'];

    // Fetch courses based on the program, semester, and exam type
    $query = "SELECT course_id, course_name FROM course WHERE program_id = :program_id AND semester = :semester";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<div class="title">
    <span>Course</span>
    <span id="exam-date">Date</span>
</div>';
    // Generate the HTML for the courses list
    foreach ($courses as $course) {
        echo '<div class="course_container">';
        
        echo '<label>' . htmlspecialchars($course['course_name']) . '</label>';
        echo '<input type="text" name="exam_data[' . htmlspecialchars($course['course_id']) . ']" class="exam_date">';
        echo '</div>';
    }
}
?>
