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
    <main>
        <div class="user-info">
            <div class="user-profile"></div>
            <h1>Rabin Pyakurel</h1>
        </div>
        <div class="attendance">
            <div class="attendance-summary">
                <div class="circle">
                    <div class="circle-inner">
                        <div class="percentage">0% <br>Attendance</div>
                    </div>
                </div>
                <p class="month">month: January</p>
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
            </div>
        </div>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/attendance.js"></script>
</body>

</html>