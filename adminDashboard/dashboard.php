<?php
 include "./db_connection.php";
 // if (isset($_SESSION['admin_id'])) {
//     $admin_id = $_SESSION['admin_id'];
//     echo '<a href="../pages/settings.php?admin_id=' . $admin_id . '">Go to Settings</a>';
// } else {
//     echo "<script>alert('Admin not logged in!'); window.location.href = 'login.php';</script>";
// }
$admin_id = 1; 
?>


<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard_styles.css">
</head>
<body>
    <div class="sidebar">
        <h2>SAPO</h2>
        <ul>
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="./pages/addStudents.php">New Student</a></li>
            <li><a href="./student_list.php">Student List</a></li>
            <li><select name="department" id="dept">Department
                <option value="event"><a href="./pages/Events manage/managevents.php">Events</a></option>
            </select></li>
            <li><a href="#">Course</a></li>
            <li><a href="#">Exams</a></li>
            <li><a href="#">Results</a></li>
            <li><a href="../adminDashboard/pages/Contact Us manage/ContactResponse.htm">Contact</a></li>
            <li><?php

echo "<a href='../pages/settings.php?admin_id=" . $admin_id . "'>Go to Settings</a>";
?>
</li>

        </ul>
    </div>
    <div class="main-content">
        <header>
            <h1>Student Academic Portal- Admin</h1>
            <div class="admin-profile">
                <img src="./adminprofile.png" alt="Admin">
                <span><b>Administrator</span>
                
                <span><b><a href="../logout.php" style="text-decoration: none; color:black;">Logout</a></span>
            </div>
        </header>
        <div class="dashboard-cards">
    <?php
   

    // Fetch the total number of departments
    $result = $connection->query("SELECT COUNT(*) AS total_departments FROM department");
    $departments = $result->fetch_assoc();

    // Fetch the total number of courses
    $result = $connection->query("SELECT COUNT(*) AS total_courses FROM course");
    $courses = $result->fetch_assoc();

    // Fetch the total number of students
    $result = $connection->query("SELECT COUNT(*) AS total_students FROM student");
    $students = $result->fetch_assoc();

    ?>

    <div class="card">Total Departments: <?= $departments['total_departments'] ?></div>
    <div class="card">Total Courses: <?= $courses['total_courses'] ?></div>
    <div class="card">Students: <?= $students['total_students'] ?></div>
    
</div>

    </div>
  

    <script src="script.js"></script>
</body>
</html>
