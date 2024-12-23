<?php
session_start();

// Database Connection
try {
    $conn = new PDO("mysql:host=localhost;dbname=sapo", "root", "rabin");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Get data from request
$month = isset($_GET['month']) ? htmlspecialchars($_GET['month']) : '';
$year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$month || !$year || !$userId) {
    echo "Missing required parameters.";
    exit;
}

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
$attendancePercentage = isset($attendanceSummary['avg_attendance']) ? round($attendanceSummary['avg_attendance'] * 100, 2) : 0;

// Generate Attendance Summary
echo '<div class="attendance-summary">
        <h2>Attendance Summary</h2>
        <div class="circle">
            <div class="circle-inner">
                <div class="percentage">' . ($attendancePercentage ?: '0') . '%</div>
            </div>
        </div>
        <p>Month: ' . htmlspecialchars($month) . ' ' . htmlspecialchars($year) . '</p>
      </div>';

echo '<!-- separator -->';

// Generate the calendar table
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), $year);
echo '<table>
        <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>';

$currentDay = 1;
$firstDay = date('w', strtotime("$year-$month-01"));

for ($i = 0; $i < 6; $i++) {
    echo '<tr>';
    for ($j = 0; $j < 7; $j++) {
        if ($i === 0 && $j < $firstDay || $currentDay > $daysInMonth) {
            echo '<td></td>';
        } else {
            $date = "$year-$month-$currentDay";
            echo "<td onclick=\"loadDailyRecord('$date')\">$currentDay</td>";
            $currentDay++;
        }
    }
    echo '</tr>';
}
echo '</table>';
?>
