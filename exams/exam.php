<?php
session_start();
require_once '../backend/db_connection.php';

// Check for session validity
if (!isset($_SESSION['user_id'])) {
    include '../not-found.htm';
    exit();
}

$student_id = $_SESSION['user_id'];

try {
    // Fetch student details
    $stmt = $pdo->prepare("SELECT semester, program_id FROM student WHERE std_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        throw new Exception("Student details not found.");
    }

    $semester = $student['semester'];
    $program_id = $student['program_id'];

    // Fetch exams grouped by exam type
    $stmt = $pdo->prepare("
        SELECT e.exam_id, et.name AS exam_type, e.exam_date, c.course_name
        FROM exam e
        JOIN course c ON e.course_id = c.course_id
        JOIN exam_type et ON e.exam_type = et.id
        WHERE c.semester = ? AND c.program_id = ?
        ORDER BY et.name, e.exam_date
    ");
    $stmt->execute([$semester, $program_id]);
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group exams by exam type
    $groupedExams = [];
    foreach ($exams as $exam) {
        $groupedExams[$exam['exam_type']][] = $exam;
    }

    // Fetch exam form statuses
    $stmt = $pdo->prepare("
        SELECT ef.form_id, et.name AS exam_type, ef.status
        FROM examform ef
        JOIN exam_examform efe ON ef.form_id = efe.form_id
        JOIN exam e ON efe.exam_id = e.exam_id
        JOIN exam_type et ON e.exam_type = et.id
        WHERE ef.std_id = ?
    ");
    $stmt->execute([$student_id]);
    $formStatuses = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $formStatuses[$row['exam_type']] = $row['status'];
    }

} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exam_type'])) {
    $examType = $_POST['exam_type'];
    
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert new exam form
        $stmt = $pdo->prepare("INSERT INTO examform (std_id) VALUES (?)");
        $stmt->execute([$student_id]);
        $formId = $pdo->lastInsertId();

        // Fetch all exam IDs for the selected exam type
        $stmt = $pdo->prepare("
            SELECT e.exam_id
            FROM exam e
            JOIN exam_type et ON e.exam_type = et.id
            WHERE et.name = ? AND e.program_id = ? AND e.semester = ?
        ");
        $stmt->execute([$examType, $program_id, $semester]);
        $examIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Insert into examform_exams
        $stmt = $pdo->prepare("INSERT INTO exam_examform (form_id, exam_id) VALUES (?, ?)");
        foreach ($examIds as $examId) {
            $stmt->execute([$formId, $examId]);
        }

        // Commit transaction
        $pdo->commit();

        $message = "Form for $examType submitted successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Schedule</title>
    <link rel="stylesheet" href="../assets/css/exam.css">
</head>

<body>
    <?php include '../layout/nav.htm'; ?>
    <main>
        <h2>Exam Schedule - Semester <?= htmlspecialchars($semester) ?></h2>

        <?php if (isset($message)): ?>
            <p class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <?php if (empty($groupedExams)): ?>
            <p class="message error">No exams available for this semester and program.</p>
        <?php else: ?>
            <?php foreach ($groupedExams as $examType => $examList): ?>
                <h3><?= htmlspecialchars($examType) ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examList as $exam): ?>
                            <tr>
                                <td><?= htmlspecialchars($exam['course_name']) ?></td>
                                <td><?= htmlspecialchars($exam['exam_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <form method="POST">
                    <input type="hidden" name="exam_type" value="<?= htmlspecialchars($examType) ?>">
                    
                    <?php if (isset($formStatuses[$examType])): ?>
                        <b>Form Status:</b> 
                        <span class="status <?= strtolower($formStatuses[$examType]) ?>">
                            <?= htmlspecialchars($formStatuses[$examType]) ?>
                        </span>
                    <?php else: ?>
                        <button type="submit" class="form-button">Fill <?= htmlspecialchars($examType) ?> Exam Form</button>
                    <?php endif; ?>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/getPhoto.js"></script>
</body>

</html>
