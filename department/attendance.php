<?php
session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="../assets/css/attendance.css">
</head>

<body>
    <?php include '../layout/nav.htm'; ?>
    <?php include '../layout/skeleton.htm' ?>
    <main style="display: none;">
        <div class="user-info">
            <div>
                <img src="" id="userImage" class="user-profile user-image" alt="user-image">
            </div>
            <h1 id="username"></h1>
        </div>
        <div class="attendance">
            <div class="attendance-summary">
                <div class="circle">
                    <div class="circle-inner">
                        <div class="percentage">0% <br>Attendance</div>
                    </div>
                </div>
                <p class="sem"><bold>Semester: </bold></p>
            </div>
            <div class="wrapper">
                <header>
                    <p class="current-date"></p>
                    <div class="icons">
                        <span id="prev">&#8249;</span>
                        <span id="next">&#8250;</span>
                    </div>
                </header>
                <div class="calendar">
                    <ul class="weeks">
                        <li>Sun</li>
                        <li>Mon</li>
                        <li>Tue</li>
                        <li>Wed</li>
                        <li>Thu</li>
                        <li>Fri</li>
                        <li>Sat</li>
                    </ul>
                    <ul class="days">
                    </ul>
                </div>
                <div class="attendance-legend">
                    <h3>Legend:</h3>
                    <ul>
                        <li><span class="status-box present"></span> Present</li>
                        <li><span class="status-box late"></span> Late</li>
                        <li><span class="status-box absent"></span> Absent</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="attendance-overview">
            <h4>Summary Table</h4>
            <hr>
            <div class="attendance-table">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Attendance</th>
                        <th>In Detail</th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    </tbody>
                </table>
            </div>
        </div>

        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3 id="modal-title">Attendance Details</h3>
                <div id="modal-details">

                </div>
            </div>
        </div>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/getPhoto.js" ></script>
    <script src="../assets/js/calendar.js"></script>
    <script src="../assets/js/attendance.js"></script>
</body>

</html>