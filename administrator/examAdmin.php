<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Admin</title>
    <link rel="stylesheet" href="../assets/css/exam_admin.css">
    <script>
        // Script for tab switching
        function openTab(event, tabName) {
            const tabs = document.querySelectorAll(".tab-content");
            const tabLinks = document.querySelectorAll(".tab-link");

            // Hide all tabs and remove active class
            tabs.forEach(tab => tab.style.display = "none");
            tabLinks.forEach(link => link.classList.remove("active"));

            // Show selected tab and set active class
            document.getElementById(tabName).style.display = "block";
            event.currentTarget.classList.add("active");
        }

        // Default tab on page load
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("defaultTab").click();
        });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Exam Admin Panel</h1>
        </header>

        <div class="tabs">
            <!-- Tab Links -->
            <button id="defaultTab" class="tab-link" onclick="openTab(event, 'formRequests')">Form Requests</button>
            <button class="tab-link" onclick="openTab(event, 'addExam')">Add Exam</button>
        </div>

        <!-- Tab Contents -->
        <div id="formRequests" class="tab-content">
            <h2>Exam Form Requests</h2>
            <?php include "form_request.php"; ?>
        </div>

        <div id="addExam" class="tab-content">
            <h2>Add New Exam</h2>
            <form method="POST" action="add_exam.php">
                <label for="exam_name">Exam Name:</label>
                <input type="text" id="exam_name" name="exam_name" required>

                <label for="exam_date">Exam Date:</label>
                <input type="date" id="exam_date" name="exam_date" required>
                
                <label for="semester">Select Semster</label>
                <select name="semester" id="semester">
                    <option value="101">BCA</option>
                </select>
                <label for="course_id">Course ID:</label>
                <input type="text" id="course_id" name="course_id" required>

                <button type="submit">Add Exam</button>
            </form>
        </div>
    </div>
</body>
</html>
