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
    <link rel="stylesheet" href="../assets/css/nav.css">
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
    <style>
/* Body styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f6;
    color: #333;
    padding: 20px;
}

/* Header styles */


/* Main content styles */
main {
    margin-top: 30px;
}

/* Attendance summary */
.attendance-summary {
    background-color: #ecf0f1;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.attendance-summary h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.circle{
    position: relative;
    margin: auto;
    width: 150px;
    height: 150px;
    background: conic-gradient(#3498db 0% 75%,
    #ddd 75% 100%);
    border-radius:50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.circle-inner {
            position: relative;
            width: 90%;
            height: 90%;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .percentage {
            font-size: 1.5em;
            font-weight: bold;
            color: #4caf50;
        }
.attendance-summary p {
    font-size: 18px;
}

/* Calendar Section */
.calendar {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.calendar h3 {
    font-size: 22px;
    margin-bottom: 15px;
}

select {
    padding: 8px 15px;
    font-size: 16px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
}

select:hover {
    border-color: #3498db;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #3498db;
    color: black;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}

table td {
    color: #555;
}

/* Footer styles */


    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#" class="logo">
                    <img src="../assets/images/Student.png" alt="Student Academic Portal">
                </a>
            </div>
            <button class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-links" id="nav-links">
                <li><a href="../home.htm">Home</a></li>
                <li><a href="./department/attendance.php">Attendance</a></li>
                <li><a href="#fee">Fee</a></li>
                <li><a href="#exam">Exam</a></li>
                <li><a href="#result">Result</a></li>
            </ul>

            <!-- Profile Dropdown on the rightmost side -->
            <ul class="nav-links right">
                <li class="dropdown" id="profile-dropdown">
                    <a href="#" class="profile-icon" id="profile-icon">
                        <img src="./assets/images/profile.png" alt="Profile Icon" class="profile-img">
                    </a>
                    <ul class="dropdown-menu" id="dropdown-menu">
                        <li><a href="#profile">My Profile</a></li>
                        <li><a href="#logout" id="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
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
    <footer id="contact" class="contact">
        <div class="container">
            <p>&copy; 2024 Student Academic Portal. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="../assets/nav.js"></script>
</body>

</html>
