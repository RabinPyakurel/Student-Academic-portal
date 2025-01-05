<?php
include "../backend/db_connection.php";

// Fetch all programs and semesters for filters
$programs = $pdo->query("SELECT program_id, program_name FROM program")->fetchAll(PDO::FETCH_ASSOC);
$semesters = $pdo->query("SELECT DISTINCT semester FROM student ORDER BY semester")->fetchAll(PDO::FETCH_ASSOC);

$year = $_GET['year'] ?? date('Y'); // Default to current year
$program_id = $_GET['program_id'] ?? null;
$semester = $_GET['semester'] ?? null;
$search = 'Rabin Babu Pyakurel';

// Build query based on filters
$query = "SELECT b.*, s.name AS student_name, s.semester, p.program_name AS program_name
          FROM billing b
          JOIN student s ON b.std_id = s.std_id
          JOIN program p ON s.program_id = p.program_id
          WHERE YEAR(b.payment_date) = :year";

$params = ['year' => $year];

if ($program_id) {
    $query .= " AND p.program_id = :program_id";
    $params['program_id'] = $program_id;
}
if ($semester) {
    $query .= " AND s.semester = :semester";
    $params['semester'] = $semester;
}
if ($search) {
    $query .= " AND s.name LIKE :search";
    $params['search'] = "%$search%";
}

$query .= " ORDER BY b.payment_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate unpaid students
$unpaid_query = "SELECT s.std_id, s.name, s.semester, p.program_name AS program_name
                 FROM student s
                 JOIN program p ON s.program_id = p.program_id
                 LEFT JOIN billing b ON s.std_id = b.std_id
                 WHERE b.billing_id IS NULL";

$unpaid_params = [];

// Only append conditions if parameters are set
if ($program_id) {
    $unpaid_query .= " AND p.program_id = :program_id";
    $unpaid_params['program_id'] = $program_id;
}
if ($semester) {
    $unpaid_query .= " AND s.semester = :semester";
    $unpaid_params['semester'] = $semester;
}

$stmt = $pdo->prepare($unpaid_query);
$stmt->execute($unpaid_params);
$unpaid_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Account Management</title>
    <link rel="stylesheet" href="billing_styles.css">
</head>
<body>
    <div class="billing-page">
        <header>
            <h1>Billing & Account Management</h1>
        </header>
        <main>
            <section class="filters">
                <form method="GET">
                    <label for="year">Year:</label>
                    <input type="number" id="year" name="year" value="<?= htmlspecialchars($year) ?>">

                    <label for="program">Program:</label>
                    <select id="program" name="program_id">
                        <option value="">All Programs</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= $program['program_id'] ?>" <?= $program_id == $program['program_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($program['program_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="semester">Semester:</label>
                    <select id="semester" name="semester">
                        <option value="">All Semesters</option>
                        <?php foreach ($semesters as $sem): ?>
                            <option value="<?= $sem['semester'] ?>" <?= $semester == $sem['semester'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sem['semester']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="search">Search:</label>
                    <input type="text" id="search" name="search" placeholder="Student Name" value="<?= htmlspecialchars($search) ?>">

                    <button type="submit">Apply Filters</button>
                </form>
            </section>

            <section class="billing-summary">
                <h2>Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Semester</th>
                            <th>Transaction Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $record): ?>
                        <tr>
                            <td><?= $record['billing_id'] ?></td>
                            <td><?= htmlspecialchars($record['student_name']) ?></td>
                            <td><?= htmlspecialchars($record['program_name']) ?></td>
                            <td><?= htmlspecialchars($record['semester']) ?></td>
                            <td><?= htmlspecialchars($record['payment_date']) ?></td>
                            <td><?= number_format($record['amount_paid'], 2) ?></td>
                            <td><?= htmlspecialchars($record['payment_status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <section class="unpaid-students">
                <h2>Unpaid Students</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($unpaid_students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['std_id']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['program_name']) ?></td>
                            <td><?= htmlspecialchars($student['semester']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
