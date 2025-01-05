<?php
include "../backend/db_connection.php";
$admin_id = 1; 

// Fetch metrics from the database
$result = $pdo->query("SELECT COUNT(*) AS total_departments FROM department");
$departments = $result->fetch(PDO::FETCH_ASSOC);

$result = $pdo->query("SELECT COUNT(*) AS total_program FROM program");
$courses = $result->fetch(PDO::FETCH_ASSOC);

$result = $pdo->query("SELECT COUNT(*) AS total_students FROM student");
$students = $result->fetch(PDO::FETCH_ASSOC);
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
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
    <h2>SAPO</h2>
    <ul>
        <li><a href="#" class="active">Dashboard</a></li>
        <li><a href="./pages/addStudents.php">New Student</a></li>
        <li><a href="./student_list.php">Student List</a></li>
        <li><a href="./pages/events_manage.php">Manage Events</a></li>
        <li><a href="#">Courses</a></li>
        <li><a href="examAdmin.php">Exams</a></li>
        <li><a href="#">Results</a></li>
        <li><a href="./contact_responses.php">Contact</a></li>
        <li><a href="billing.php">Billing & Account</a></li>
        <li><a href="../pages/settings.php?admin_id=<?= $admin_id ?>">Settings</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Welcome, Administrator</h1>
            </header>

            <!-- Dashboard Metrics -->
            <section class="dashboard-metrics">
                <div class="card">Total Departments: <?= $departments['total_departments'] ?></div>
                <div class="card">Total Programs: <?= $courses['total_program'] ?></div>
                <div class="card">Total Students: <?= $students['total_students'] ?></div>
            </section>

            <!-- Modules Section -->
            <section class="modules">
                <h2>Quick Actions</h2>
                <div class="module-container">
                    <!-- Add Student -->
                    <div class="module">
                        <h3>Add Student</h3>
                        <p>Register a new student to the system.</p>
                        <a href="./pages/addStudents.php" class="btn">Go to Add Student</a>
                    </div>

                    <!-- Manage Programs -->
                    <div class="module">
                        <h3>Manage Programs</h3>
                        <p>Edit, delete, or add new academic programs.</p>
                        <a href="#" class="btn">Manage Programs</a>
                    </div>

                    <!-- Event Management -->
                    <div class="module">
                        <h3>Event Management</h3>
                        <p>Organize and oversee upcoming events.</p>
                        <a href="./pages/events_manage.php" class="btn">Manage Events</a>
                    </div>
                </div>
            </section>

            <!-- Recent Activities Section -->
            <section class="data-tables">
                <h2>Recent Activities</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Event Created</td>
                            <td>2025-01-01</td>
                            <td>Completed</td>
                        </tr>
                        <tr>
                            <td>Student Registered</td>
                            <td>2025-01-02</td>
                            <td>Pending</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
