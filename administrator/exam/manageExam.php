<?php
session_start();
require_once __DIR__ . '/../../backend/db_connection.php';

// Check if admin is logged in


try {
    // Fetch all exam forms
    $stmt = $pdo->prepare("
        SELECT ef.form_id, 
               s.std_id, 
               s.name,  
               ef.status, 
               ef.submission_date
        FROM examform ef
        JOIN student s ON ef.std_id = s.std_id
        ORDER BY ef.submission_date DESC
    ");
    $stmt->execute();
    $examForms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $formDetails = null;
    $examCourses = [];

    // Fetch detailed data when a specific form is viewed
    if (isset($_GET['form_id'])) {
        $formId = $_GET['form_id'];

        // Fetch form details
        $stmt = $pdo->prepare("
            SELECT ef.form_id, 
                   s.std_id, 
                   s.name,  
                   s.email,  
                   s.semester, 
                   ef.status
            FROM examform ef
            JOIN student s ON ef.std_id = s.std_id
            WHERE ef.form_id = ?
        ");
        $stmt->execute([$formId]);
        $formDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($formDetails) {
            // Fetch exams related to this form
            $stmt = $pdo->prepare("
                SELECT e.exam_id, 
                       et.name AS exam_type, 
                       e.exam_date, 
                       c.course_name
                FROM exam_examform efe
                JOIN exam e ON efe.exam_id = e.exam_id
                JOIN course c ON e.course_id = c.course_id
                JOIN exam_type et ON e.exam_type = et.id
                WHERE efe.form_id = ?
            ");
            $stmt->execute([$formId]);
            $examCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}

// Handle Accept/Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_id'], $_POST['action'])) {
    $formId = $_POST['form_id'];
    $action = $_POST['action']; // Accept or Reject

    try {
        // Update exam form status
        $stmt = $pdo->prepare("UPDATE examform SET status = ? WHERE form_id = ?");
        $stmt->execute([$action, $formId]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => htmlspecialchars($e->getMessage())]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Exam Forms</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        section {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
        }

        .pending {
            background-color: yellow;
        }

        .approved {
            background-color: green;
            color: white;
        }

        .rejected {
            background-color: red;
            color: white;
        }

        button {
            padding: 8px 16px;
            margin: 5px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            border-radius: 5px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button[type="submit"][name="action='Rejected'"] {
            background-color: #f44336;
        }

        button[type="submit"][name="action='Rejected'"]:hover {
            background-color: #e53935;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            button {
                font-size: 12px;
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>
    <main>
        <h2>Manage Exam Forms</h2>

        <!-- List of Exam Forms -->
        <section>
            <h3>All Exam Forms</h3>
            <?php if (empty($examForms)): ?>
                <p>No exam forms submitted yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Form ID</th>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th>Submission Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examForms as $form): ?>
                            <tr>
                                <td><?= htmlspecialchars($form['form_id']) ?></td>
                                <td><?= htmlspecialchars($form['name']) ?></td>
                                <td class="<?= strtolower($form['status']) ?>">
                                    <?= htmlspecialchars($form['status']) ?>
                                </td>
                                <td><?= htmlspecialchars($form['submission_date']) ?></td>
                                <td>
                                    <a href="?form_id=<?= htmlspecialchars($form['form_id']) ?>">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <!-- Detailed Form View -->
        <?php if ($formDetails): ?>
            <section>
                <h3>Form Details - Form ID <?= htmlspecialchars($formDetails['form_id']) ?></h3>
                <h4>Student Details</h4>
                <p><b>Student ID:</b> <?= htmlspecialchars($formDetails['std_id']) ?></p>
                <p><b>Name:</b> <?= htmlspecialchars($formDetails['name'] )?></p>
                <p><b>Email:</b> <?= htmlspecialchars($formDetails['email']) ?></p>
    
                <p><b>Semester:</b> <?= htmlspecialchars($formDetails['semester']) ?></p>
                <p><b>Status:</b> <?= htmlspecialchars($formDetails['status']) ?></p>

                <h4>Exams</h4>
                <?php if (empty($examCourses)): ?>
                    <p>No exams associated with this form.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Exam Type</th>
                                <th>Course</th>
                                <th>Exam Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($examCourses as $exam): ?>
                                <tr>
                                    <td><?= htmlspecialchars($exam['exam_type']) ?></td>
                                    <td><?= htmlspecialchars($exam['course_name']) ?></td>
                                    <td><?= htmlspecialchars($exam['exam_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if ($formDetails['status'] === 'Pending'): ?>
                    <form method="POST">
                        <input type="hidden" name="form_id" value="<?= htmlspecialchars($formDetails['form_id']) ?>">
                        <button type="submit" name="action" value="Approved">Accept</button>
                        <button type="submit" name="action" value="Rejected">Reject</button>
                    </form>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
