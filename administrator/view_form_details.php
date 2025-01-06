<?php
include "../backend/db_connection.php";

if (isset($_GET['form_id'])) {
    $formId = $_GET['form_id'];

    try {
        // Fetch the general details of the form (student and submission details)
        $stmt = $pdo->prepare("
            SELECT ef.form_id, ef.submission_date, ef.status,
                   s.std_id, s.name, s.semester, s.program_id
            FROM examform ef
            JOIN student s ON ef.std_id = s.std_id
            WHERE ef.form_id = ?
            LIMIT 1
        ");
        $stmt->execute([$formId]);
        $details = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$details) {
            throw new Exception("No details found for the selected form.");
        }

        // Fetch all subjects (courses) associated with this form
        $stmtSubjects = $pdo->prepare("
            SELECT c.course_name, c.semester, e.exam_name, e.exam_date
            FROM examform ef
            JOIN exam e ON ef.exam_id = e.exam_id
            JOIN course c ON e.course_id = c.course_id
            WHERE ef.form_id = ?
        ");
        $stmtSubjects->execute([$formId]);
        $subjects = $stmtSubjects->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Error: No form ID provided.");
}
?>

<h2>Form Details</h2>
<table>
    <tr><th>Form ID:</th><td><?= htmlspecialchars($details['form_id']); ?></td></tr>
    <tr><th>Submission Date:</th><td><?= htmlspecialchars($details['submission_date']); ?></td></tr>
    <tr><th>Status:</th><td><?= htmlspecialchars($details['status']); ?></td></tr>
    <tr><th>Student Name:</th><td><?= htmlspecialchars($details['name']); ?></td></tr>
    <tr><th>Semester:</th><td><?= htmlspecialchars($details['semester']); ?></td></tr>
    <tr><th>Program ID:</th><td><?= htmlspecialchars($details['program_id']); ?></td></tr>
</table>

<h3>Subjects</h3>
<?php if (count($subjects) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Subject Name</th>
                <th>Semester</th>
                <th>Exam Name</th>
                <th>Exam Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?= htmlspecialchars($subject['course_name']); ?></td>
                    <td><?= htmlspecialchars($subject['semester']); ?></td>
                    <td><?= htmlspecialchars($subject['exam_name']); ?></td>
                    <td><?= htmlspecialchars($subject['exam_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No subjects found for this form.</p>
<?php endif; ?>

<a href="examAdmin.php">Back</a>
