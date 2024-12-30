<!-- <?php include("./header.php"); ?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./admin_style.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include("./sidebar.php"); ?>

        <main class="main-content">
            <h1>Welcome to the Admin Dashboard</h1>
            <section id="overview">
                <h2>Quick Links</h2>
                <div class="card-container">
                    <a href="#" class="card">
                        <img src="assets/icons/exams.png" alt="Exams">
                        <p>Exams</p>
                    </a>
                    <a href="#" class="card">
                        <img src="assets/icons/results.png" alt="Results">
                        <p>Results</p>
                    </a>
                    <a href="#" class="card">
                        <img src="assets/icons/fees.png" alt="Fees">
                        <p>Fees</p>
                    </a>
                    <a href="#" class="card">
                        <img src="assets/icons/attendance.png" alt="Attendance">
                        <p>Attendance</p>
                    </a>
                    <a href="#" class="card">
                        <img src="assets/icons/library.png" alt="Library">
                        <p>Library</p>
                    </a>
                </div>
            </section>
        </main>
    </div>
    <script src="./admin_script.js"></script>
</body>
</html>
