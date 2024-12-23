<?php

session_start();
try {
    $conn = new PDO("mysql:host=localhost;dbname=sapo", "root", "rabin");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}


$userId =  $_SESSION['user_id']; 
$currentMonth = date('F');
$currentYear = date('Y');


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
    <link rel="stylesheet" href="style.css">
    
    <script>
     // Function to update the calendar and attendance summary dynamically
function updateCalendar() {
    const monthSelector = document.getElementById("month");
    const selectedMonth = monthSelector.value;
    const year = new Date().getFullYear();

    fetch(`attendance_data.php?month=${selectedMonth}&year=${year}`)
        .then((response) => response.text())
        .then((data) => {
            const dataParts = data.split("<!-- separator -->");

            // Update the attendance summary and calendar dynamically
            document.querySelector(".attendance-summary").innerHTML = dataParts[0];
            document.getElementById("calendar-container").innerHTML = dataParts[1];

            // Extract percentage value from the updated attendance summary
            const percentageElement = document.querySelector(".percentage");
            const percentage = parseFloat(percentageElement.textContent.replace('%', '')) || 0;

            // Apply gradient dynamically based on percentage
            const circle = document.querySelector(".circle");
            circle.style.background = `conic-gradient(
                #4caf50 ${percentage * 3.6}deg, 
                #f3f4f6 ${percentage * 3.6}deg
            )`;
        })
        .catch((error) => console.error("Error fetching attendance data:", error));
}

// Function to load attendance data for a specific day
function loadDailyRecord(selectedDate) {
    fetch(`attendance_day_data.php?date=${selectedDate}`)
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("daily-record-container").innerHTML = data;
        })
        .catch((error) => console.error("Error fetching daily record:", error));
}

</script>
</head>

<body>
    <!-- Navbar -->
   <?php include '../layout/nav.htm' ?>
    <main>
        <div class="attendance-summary">
            <h2>Attendance Summary</h2>
            <div class="circle">
                <div class="circle-inner">
                    <div class="percentage"><?= $attendancePercentage ?: '0'; ?>%</div>
                </div>
            </div>
            <p>Month: <?= $currentMonth . ' ' . $currentYear; ?></p>
        </div>

        <div class="calendar-section">
        <h3>Attendance Calendar</h3>
        <label for="month">Select Month:</label>
        <select id="month" onchange="updateCalendar()">
            <!-- Month Options -->
            <?php
            $months = [
                'January', 'February', 'March', 'April', 'May', 'June', 
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            foreach ($months as $month) {
                $selected = ($currentMonth == $month) ? 'selected' : '';
                echo "<option value='$month' $selected>$month</option>";
            }
            ?>
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
   <?php include '../layout/footer.htm' ?>
</body>

</html>
