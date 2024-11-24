<?php
session_start();

// Error handling
try {
    $conn = new PDO("mysql:host=localhost;dbname=sapo", "root", "rabin");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Get data from request
$month = isset($_GET['month']) ? htmlspecialchars($_GET['month']) : ''; // Sanitize input
$year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Replace with session-based user ID

// Validate the inputs
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
        <p>Month: ' . htmlspecialchars($month) . ' ' . htmlspecialchars($year) . '</p>
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
          $status = htmlspecialchars($detail['status'] ?? '');  // Replace null with empty string
          $remarks = htmlspecialchars($detail['remarks'] ?? '');  // Replace null with empty string
      
          echo '<tr>
                  <td>' . date('d F Y', strtotime($detail['date'])) . '</td>
                  <td>' . $status . '</td>
                  <td>' . $remarks . '</td>
                </tr>';
      }
      echo '</table>';
      

?>
