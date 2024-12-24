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
                <p class="month"><bold>Month: </bold>January</p>
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
        
        <div class="attendance-overview">
            <h4>Summary Table</h4>
            <hr>
            <div class="attendance-table">
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Attendance (in %)</th>
                        <th>In Detail</th>
                    </tr>
                    <tr>
                        <td>December, 2024</td>
                        <td>91%</td>
                        <td><a href="">View</a></td>
                    </tr>
                    <tr>
                        <td>November, 2024</td>
                        <td>89%</td>
                        <td><a href="">View</a></td>
                    </tr>
                    <tr>
                        <td>October, 2024</td>
                        <td>85%</td>
                        <td><a href="">View</a></td>
                    </tr>
                    <tr>
                        <td>September, 2024</td>
                        <td>97%</td>
                        <td><a href="">View</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/attendance.js"></script>
</body>

</html>