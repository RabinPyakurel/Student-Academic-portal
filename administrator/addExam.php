<?php
include "../backend/db_connection.php";

// Fetch available semesters and programs
$stmt = $pdo->prepare("SELECT DISTINCT semester FROM course");
$stmt->execute();
$semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT DISTINCT program_id FROM course");
$stmt->execute();
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch courses for the selected semester and program when the form is submitted
$courses = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['semester']) && isset($_POST['program_id'])) {
    $semester = $_POST['semester'];
    $program_id = $_POST['program_id'];

    // Fetch courses for the selected semester and program
    $stmt = $pdo->prepare("SELECT course_id, course_name FROM course WHERE semester = ? AND program_id = ?");
    $stmt->execute([$semester, $program_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Semester and Program Selection Form -->
<form method="POST" action="">
    <label for="semester">Select Semester:</label>
    <select name="semester" id="semester">
        <?php foreach ($semesters as $semester): ?>
            <option value="<?= $semester['semester']; ?>" <?= isset($semester) && $semester['semester'] == $semester ? 'selected' : ''; ?>>
                Semester <?= $semester['semester']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="program_id">Select Program:</label>
    <select name="program_id" id="program_id">
        <?php foreach ($programs as $program): ?>
            <option value="<?= $program['program_id']; ?>" <?= isset($program) && $program['program_id'] == $program_id ? 'selected' : ''; ?>>
                Program <?= $program['program_id']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Show Courses</button>
</form>

<!-- If courses are available for the selected semester and program -->
<?php if (!empty($courses)): ?>
    <form method="POST" action="bulk_add_exam.php">
        <input type="hidden" name="semester" value="<?= $semester; ?>">
        <input type="hidden" name="program_id" value="<?= $program_id; ?>">

        <h3>Enter Exam Dates for the Courses</h3>
        
        <?php foreach ($courses as $course): ?>
            <div>
                <label for="exam_date_<?= $course['course_id']; ?>"><?= htmlspecialchars($course['course_name']); ?> Exam Date:</label>
                <input type="date" name="exam_dates[<?= $course['course_id']; ?>]" required>
            </div>
        <?php endforeach; ?>

        <button type="submit">Submit Exam Dates</button>
    </form>
<?php endif; ?>
