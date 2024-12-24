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
    <title>Exam and Results</title>
    <link rel="stylesheet" href="./assets/css/nav.css">
    <style>
       
        main {
            padding: 20px;
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 2px solid #cccccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .exam-schedule {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f7f7f7;
            border-bottom: 2px solid #cccccc;

        }

        .exam-schedule .title {
            font-size: 20px;
            font-weight: bold;

        }

        .exam-schedule .search-box {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 5px;
            padding: 5px;
        }

        .exam-schedule .search-box select {
            border: none;
            outline: none;
            margin-right: 5px;
            padding: 5px;
            font-size: 14px;
        }

        .exam-schedule .search-box button {
            background-color: #0073e6;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .exam-schedule .search-box button:hover {
            background-color: #005bb5;
        }

        .upcoming-exams {
            padding: 20px;
            background-color: #d9d9d9;
            margin: 20px 0;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .results-section {
            padding: 20px;
        }

        .results-section .title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #cccccc;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .exam-schedule {
                flex-direction: column;
                align-items: flex-start;
            }

            .exam-schedule .search-box {
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include '../layout/nav.htm' ?>
    <main>
        <section class="exam-schedule">
            <div class="title">Exam Schedule</div>
            <div class="search-box">
                <select>
                    <option>Subject</option>
                    <option>Date</option>
                    <option>Course</option>
                    <option>Semester</option>
                </select>
                <button>Search</button>
            </div>
        </section>
        <div class="upcoming-exams">Upcoming Exams</div>
        <section class="results-section">
            <div class="title">Results</div>
        </section>
        </main>
        <?php include '../layout/footer.htm' ?>
</body>

</html>