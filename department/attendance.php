<?php
// attendance.php
session_start();
// Database connection
try {
    $conn = new PDO("mysql:host=localhost;dbname=sapo", "root", "rabin");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Default values
$userId =  $_SESSION['user_id']; // Replace with a session-based user ID
$currentMonth = date('F');
$currentYear = date('Y');

// Function to get the number of days in a month
function getDaysInMonth($month, $year) {
    return cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), $year);
}

// Fetch attendance percentage for the current month
$stmt = $conn->prepare("SELECT AVG(CASE 
                                            WHEN status = 'Present' THEN 1 
                                            WHEN status = 'Late' THEN 0.5 
                                            ELSE 0 
                                        END) as avg_attendance 
                        FROM attendance 
                        WHERE std_id = :user_id 
                          AND MONTHNAME(date) = :month");
$stmt->execute([':user_id' => $userId, ':month' => $currentMonth]);
$attendanceSummary = $stmt->fetch(PDO::FETCH_ASSOC);
$attendancePercentage = round($attendanceSummary['avg_attendance'] * 100, 2);

// Fetch attendance details for the current month
$stmt = $conn->prepare("SELECT date, status, remarks 
                        FROM attendance 
                        WHERE std_id = :user_id 
                          AND MONTHNAME(date) = :month 
                        ORDER BY date ASC");
$stmt->execute([':user_id' => $userId, ':month' => $currentMonth]);
$attendanceDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS here -->
    <script>
       // JavaScript to handle dynamic month updates
       function updateCalendar() {
        const monthSelector = document.getElementById("month");
        const selectedMonth = monthSelector.value;
        const year = new Date().getFullYear();

        // Send AJAX request to fetch data for the selected month
        fetch(`attendance_data.php?month=${selectedMonth}&year=${year}`)
            .then((response) => response.text())
            .then((data) => {
                // Split the response into attendance summary and calendar table parts
                const dataParts = data.split("<!-- separator -->");

                // Update the attendance summary and calendar dynamically
                document.querySelector(".attendance-summary").innerHTML = dataParts[0];
                document.getElementById("calendar-container").innerHTML = dataParts[1];
            });
    }
    </script>
</head>

<body>
    <header>
        <div class="logo">LOGO</div>
        <nav>
            <a href="/home.htm">Home</a>
            <a href="#">Attendance</a>
            <a href="#">Fee</a>
            <a href="#">Exam and Results</a>
            <a href="#">Library</a>
            <a href="#">About Us</a>
        </nav>
    </header>
    <main>
        <div class="attendance-summary">
            <h2>Attendance Summary</h2>
            <div class="attendance-circle">
                <span><?= $attendancePercentage ?: '0'; ?>%</span>
            </div>
            <p>Month: <?= $currentMonth . ' ' . $currentYear; ?></p>
        </div>

        <div class="calendar">
            <h3>Attendance Calendar</h3>
            <label for="month">Select Month:</label>
            <select id="month" onchange="updateCalendar()">
                <option value="January" <?= $currentMonth == 'January' ? 'selected' : ''; ?>>January</option>
                <option value="February" <?= $currentMonth == 'February' ? 'selected' : ''; ?>>February</option>
                <option value="March" <?= $currentMonth == 'March' ? 'selected' : ''; ?>>March</option>
                <option value="April" <?= $currentMonth == 'April' ? 'selected' : ''; ?>>April</option>
                <option value="May" <?= $currentMonth == 'May' ? 'selected' : ''; ?>>May</option>
                <option value="June" <?= $currentMonth == 'June' ? 'selected' : ''; ?>>June</option>
                <option value="July" <?= $currentMonth == 'July' ? 'selected' : ''; ?>>July</option>
                <option value="August" <?= $currentMonth == 'August' ? 'selected' : ''; ?>>August</option>
                <option value="September" <?= $currentMonth == 'September' ? 'selected' : ''; ?>>September</option>
                <option value="October" <?= $currentMonth == 'October' ? 'selected' : ''; ?>>October</option>
                <option value="November" <?= $currentMonth == 'November' ? 'selected' : ''; ?>>November</option>
                <option value="December" <?= $currentMonth == 'December' ? 'selected' : ''; ?>>December</option>
            </select>
            <div id="calendar-container">
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                    <?php foreach ($attendanceDetails as $detail) : ?>
                        <tr>
                            <td><?= date('d F Y', strtotime($detail['date'])); ?></td>
                            <td><?= $detail['status']; ?></td>
                            <td><?= $detail['remarks']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Student Academic Portal. All Rights Reserved.</p>
    </footer>
</body>

</html>
