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
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
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
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Attendance (in %)</th>
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
                <div class="modal-details">

                </div>
            </div>
        </div>
    </main>
    <?php include '../layout/footer.htm'; ?>
    <script src="../assets/js/calendar.js"></script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: 'attendance_data.php',
                method: 'GET',
                data: {
                    action: 'monthly'
                },
                dataType: 'json',
                success: function (data) {
                    const tableBody = $('#table-body');
                    tableBody.empty();
                    data.forEach((item) => {
                        tableBody.append(`<tr>
                                <td>${item.month_year}</td>
                                <td>${item.attendance_percentage}%</td>
                                <td><a href="#" class="view-details" data-date="${item.month_year}">View</a></td>
                            </tr>`);
                    });
                }
            });

            $.ajax({
                url: 'attendance_data.php',
                method: 'GET',
                data: {
                    action: 'overall'
                },
                dataType: 'json',
                success: function (data) {
                    const percentage = data[0].attendance_percentage;
                    $('.percentage').text(`${percentage}% Attendance`);
                    $('.circle').css('background', `conic-gradient(#3498db ${percentage}%, #ddd ${percentage}% 100%)`);
                }
            });

            $(document).on('click','[data-date]',function(e){
                e.preventDefault();
                const monthYear = $(this).data('date');
            })
        });
    </script>
   
</body>

</html>