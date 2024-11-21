<?php
// attendance_data.php
session_start();
$conn = new PDO("mysql:host=localhost;dbname=sapo", "root", "rabin");

// Get data from request
$month = $_GET['month'];
$year = $_GET['year'];
$userId =  $_SESSION['user_id']; // Replace with session-based user ID

// Fetch attendance percentage for the selected month
$stmt = $conn->prepare("SELECT AVG(CASE 
                                        WHEN status = 'Present' THEN 1 
                                        WHEN status = 'Late' THEN 0.5 
                                        ELSE 0 
                                    END) as avg_attendance 
                        FROM attendance 
                        WHERE std_id = :user_id 
                          AND MONTHNAME(date) = :month 
                          AND YEAR(date) = :year");
$stmt->execute([':user_id' => $userId, ':month' => $month, ':year' => $year]);
$attendanceSummary = $stmt->fetch(PDO::FETCH_ASSOC);
$attendancePercentage = round($attendanceSummary['avg_attendance'] * 100, 2);

// Fetch attendance details for the selected month
$stmt = $conn->prepare("SELECT date, status, remarks 
                        FROM attendance 
                        WHERE std_id = :user_id 
                          AND MONTHNAME(date) = :month 
                          AND YEAR(date) = :year 
                        ORDER BY date ASC");
$stmt->execute([':user_id' => $userId, ':month' => $month, ':year' => $year]);
$attendanceDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the HTML for the updated attendance summary and table
echo '<div class="attendance-summary">
        <h2>Attendance Summary</h2>
        <div class="attendance-circle">
            <span>' . ($attendancePercentage ?: '0') . '%</span>
        </div>
        <p>Month: ' . $month . ' ' . $year . '</p>
      </div>';

echo '<!-- separator -->'; // This separator is used to split the data in JS

// Generate table rows for the calendar
echo '<table>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>';
foreach ($attendanceDetails as $detail) {
    echo '<tr>
            <td>' . date('d F Y', strtotime($detail['date'])) . '</td>
            <td>' . $detail['status'] . '</td>
            <td>' . $detail['remarks'] . '</td>
          </tr>';
}
echo '</table>';
?>
