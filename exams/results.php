<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    include '../not-found.htm';
    exit();
}

// Database connection
require_once '../backend/db_connection.php';

// Fetch user ID from session
$student_id = $_SESSION['user_id'];

try {
    // Fetch results for the logged-in student
    $stmt = $pdo->prepare("
        SELECT 
            c.course_name,
            m.marks_obtained,
            e.exam_name,
            e.exam_date
        FROM marks m
        JOIN course c ON m.course_id = c.course_id
        JOIN exam e ON m.exam_id = e.exam_id
        WHERE m.std_id = ?
        ORDER BY e.exam_date DESC, c.course_name ASC
    ");
    $stmt->execute([$student_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching results: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="../assets/css/result.css">
</head>
<body>
    <?php include '../layout/nav.htm'; ?>
    <main>
        <section class="result-section">
            <h1>Your Exam Results</h1>

            <?php if (empty($results)): ?>
                <p class="no-results">No results available at the moment. Please check back later.</p>
            <?php else: ?>
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Exam</th>
                            <th>Marks Obtained</th>
                            <th>Exam Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr>
                                <td><?= htmlspecialchars($result['course_name']); ?></td>
                                <td><?= htmlspecialchars($result['exam_name']); ?></td>
                                <td><?= htmlspecialchars($result['marks_obtained']); ?></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($result['exam_date']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
    <?php include '../layout/footer.htm'; ?>
</body>
</html>
