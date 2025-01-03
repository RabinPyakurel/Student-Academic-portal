<?php
session_start();
include '../backend/db_connection.php';



// Fetch all billing records
$query = "SELECT b.billing_id, b.semester, b.total_fee, b.amount_paid, b.payment_status, b.payment_method, b.payment_date,
                 s.name AS student_name
          FROM billing b
          JOIN student s ON b.std_id = s.std_id
          ORDER BY b.payment_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$billingRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Billing Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-button {
            padding: 5px 10px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Admin Billing Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>Billing ID</th>
                <th>Student Name</th>
                <th>Semester</th>
                <th>Total Fee</th>
                <th>Amount Paid</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($billingRecords as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['billing_id']) ?></td>
                <td><?= htmlspecialchars($record['student_name']) ?></td>
                <td><?= htmlspecialchars($record['semester']) ?></td>
                <td><?= htmlspecialchars($record['total_fee']) ?></td>
                <td><?= htmlspecialchars($record['amount_paid']) ?></td>
                <td><?= htmlspecialchars($record['payment_status']) ?></td>
                <td><?= htmlspecialchars($record['payment_date']) ?></td>
                <td>
                    <a class="action-button" href="../account/generate-bill.php?= urlencode($record['billing_id']) ?>" target="_blank">View Receipt</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
