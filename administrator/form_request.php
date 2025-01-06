<?php
include "../backend/db_connection.php";

try {
    // Fetch all exam form requests
    $stmt = $pdo->prepare("
        SELECT ef.form_id, ef.std_id, ef.exam_id, ef.submission_date, ef.status,
               s.name, e.exam_name, e.exam_date
        FROM examform ef
        JOIN student s ON ef.std_id = s.std_id
        JOIN exam e ON ef.exam_id = e.exam_id
        ORDER BY ef.submission_date DESC
    ");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<table>
    <thead>
        <tr>
            <th>Form ID</th>
            <th>Student Name</th>
            <th>Exam Name</th>
            <th>Exam Date</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['form_id']); ?></td>
                <td><?= htmlspecialchars($request['name']); ?></td>
                <td><?= htmlspecialchars($request['exam_name']); ?></td>
                <td><?= htmlspecialchars($request['exam_date']); ?></td>
                <td><?= htmlspecialchars($request['submission_date']); ?></td>
                <td><?= htmlspecialchars($request['status']); ?></td>
                <td>
                    <form method="POST" action="update_form_status.php" style="display:inline;">
                        <input type="hidden" name="form_id" value="<?= $request['form_id']; ?>">
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" style="background-color: green; color: white; border: none; padding: 5px;">Accept</button>
                    </form>
                    <form method="POST" action="update_form_status.php" style="display:inline;">
                        <input type="hidden" name="form_id" value="<?= $request['form_id']; ?>">
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px;">Reject</button>
                    </form>
                    <form method="GET" action="view_form_details.php" style="display:inline;">
                        <input type="hidden" name="form_id" value="<?= $request['form_id']; ?>">
                        <button type="submit" style="background-color: blue; color: white; border: none; padding: 5px;">View Details</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
