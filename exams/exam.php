<?php
session_start();
require_once '../backend/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    include '../not-found.htm';
    exit();
}


$student_id = $_SESSION['user_id'];

try {

    $stmt = $pdo->prepare("SELECT semester, program_id FROM student WHERE std_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        throw new Exception("Student details not found.");
    }

    $semester = $student['semester'];
    $program_id = $student['program_id'];

   
    $stmt = $pdo->prepare("
        SELECT e.exam_id, e.exam_name, e.exam_date, c.course_name
        FROM exam e
        JOIN course c ON e.course_id = c.course_id
        WHERE c.semester = ? AND c.program_id = ?
        ORDER BY e.exam_name, e.exam_date
    ");
    $stmt->execute([$semester, $program_id]);
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
    $groupedExams = [];
    foreach ($exams as $exam) {
        $groupedExams[$exam['exam_name']][] = $exam;
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exam_name'])) {
    $examName = $_POST['exam_name'];
    
    try {
       
        $stmt = $pdo->prepare("
            SELECT e.exam_id
            FROM exam e
            JOIN course c ON e.course_id = c.course_id
            WHERE e.exam_name = ? AND c.semester = ? AND c.program_id = ?
        ");
        $stmt->execute([$examName, $semester, $program_id]);
        $examIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
       
        foreach ($examIds as $examId) {
            $stmt = $pdo->prepare("
                INSERT IGNORE INTO examform (std_id, exam_id) VALUES (?, ?)
            ");
            $stmt->execute([$student_id, $examId]);
        }
        
        $message = "$examName Exam Form submitted successfully!";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
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
    <?php foreach ($groupedExams as $examName => $examList): ?>
        <h3 class="h3"><?= htmlspecialchars($examName) ?></h3>
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
    <input type="hidden" name="exam_name" value="<?= htmlspecialchars($examName) ?>">

    <?php
    // Check if the student has already filled the form and retrieve its status
    $stmt = $pdo->prepare("
        SELECT ef.status
        FROM examform ef
        JOIN exam e ON ef.exam_id = e.exam_id
        WHERE ef.std_id = ? AND e.exam_name = ?
        LIMIT 1
    ");
    $stmt->execute([$student_id, $examName]);
    $status = $stmt->fetchColumn();

    // Display based on the retrieved status
    if ($status): ?>
        <b>Form Status:</b> <span class="status <?= strtolower($status) ?>"><?= htmlspecialchars($status) ?></span>
    <?php else: ?>
        <button type="submit" class="form-button">Fill <?= htmlspecialchars($examName) ?> Exam Form</button>
    <?php endif; ?>
</form>

    <?php endforeach; ?>
<?php endif; ?>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/getPhoto.js"></script>
</body>

</html>
