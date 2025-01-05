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
    <link rel="stylesheet" href="./assets/css/nav.css">
    <style>
        main {
            padding: 20px;
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 2px solid #cccccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 75vh;
        }

        h3 {
            margin-top: 30px;
            color: #0073e6;
            font-size: 1.4em;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        th {
            background-color: #0073e6;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .form-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-button:hover {
            background-color: #218838;
        }

        .message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 1em;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
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
        <h3><?= htmlspecialchars($examName) ?></h3>
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
            <button type="submit" class="form-button">Fill <?= htmlspecialchars($examName) ?> Exam Form</button>
        </form>
    <?php endforeach; ?>
<?php endif; ?>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/getPhoto.js"></script>
</body>

</html>
