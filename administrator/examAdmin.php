<?php
include "../backend/db_connection.php";

// Fetch Programs and Semesters
$programs = $pdo->query("SELECT program_id, program_name FROM program")->fetchAll(PDO::FETCH_ASSOC);
$semesters = $pdo->query("SELECT DISTINCT semester FROM course ORDER BY semester")->fetchAll(PDO::FETCH_ASSOC);

// Handle Exam Allocation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['allocate_exam'])) {
    $program_id = $_POST['program_id'];
    $semester = $_POST['semester'];
    $exam_name = $_POST['exam_name'];
    $exam_date = $_POST['exam_date'];
    $courses = $_POST['courses'];

    foreach ($courses as $course_id) {
        $stmt = $pdo->prepare("INSERT INTO exam (program_id, semester, exam_name, exam_date, course_id) 
                               VALUES (:program_id, :semester, :exam_name, :exam_date, :course_id)");
        $stmt->execute([
            ':program_id' => $program_id,
            ':semester' => $semester,
            ':exam_name' => $exam_name,
            ':exam_date' => $exam_date,
            ':course_id' => $course_id,
        ]);
    }
}

// Fetch Exams and Exam Form Requests
$exam_forms = $pdo->query("SELECT ef.*, s.name AS student_name, ex.exam_name 
                           FROM examform ef
                           JOIN student s ON ef.std_id = s.std_id
                           JOIN exam ex ON ef.exam_id = ex.exam_id
                           WHERE ef.status = 'Pending'")->fetchAll(PDO::FETCH_ASSOC);

$allocated_exams = $pdo->query("SELECT e.*, p.program_name 
                                FROM exam e
                                JOIN program p ON e.program_id = p.program_id
                                ORDER BY e.exam_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Admin</title>
    <link rel="stylesheet" href="../assets/css/exam_admin.css">
</head>
<body>
    <div class="exam-admin-page">
        <header>
            <h1>Exam Management</h1>
        </header>

        <main>
            <!-- Exam Allocation -->
            <section class="exam-allocation">
                <h2>Allocate Exam</h2>
                <form method="POST">
                    <label for="program">Program:</label>
                    <select id="program" name="program_id" required>
                        <option value="">Select Program</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= $program['program_id'] ?>"><?= htmlspecialchars($program['program_name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="semester">Semester:</label>
                    <select id="semester" name="semester" required>
                        <option value="">Select Semester</option>
                        <?php foreach ($semesters as $sem): ?>
                            <option value="<?= $sem['semester'] ?>"><?= htmlspecialchars($sem['semester']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="exam_name">Exam Name:</label>
                    <input type="text" id="exam_name" name="exam_name" required>

                    <label for="exam_date">Exam Date:</label>
                    <input type="date" id="exam_date" name="exam_date" required>

                    <label for="courses">Courses:</label>
                    <select id="courses" name="courses[]" multiple required>
                        <!-- Courses will be loaded dynamically -->
                    </select>

                    <button type="submit" name="allocate_exam">Allocate Exam</button>
                </form>
            </section>

            <!-- View Allocated Exams -->
            <section class="allocated-exams">
                <h2>Allocated Exams</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Program</th>
                            <th>Semester</th>
                            <th>Course</th>
                            <th>Exam Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allocated_exams as $exam): ?>
                            <tr>
                                <td><?= htmlspecialchars($exam['exam_name']) ?></td>
                                <td><?= htmlspecialchars($exam['program_name']) ?></td>
                                <td><?= htmlspecialchars($exam['semester']) ?></td>
                                <td><?= htmlspecialchars($exam['course_id']) ?></td>
                                <td><?= htmlspecialchars($exam['exam_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Exam Form Requests -->
            <section class="exam-forms">
                <h2>Exam Form Requests</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Form ID</th>
                            <th>Student Name</th>
                            <th>Exam Name</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exam_forms as $form): ?>
                            <tr>
                                <td><?= htmlspecialchars($form['form_id']) ?></td>
                                <td><?= htmlspecialchars($form['student_name']) ?></td>
                                <td><?= htmlspecialchars($form['exam_name']) ?></td>
                                <td><?= htmlspecialchars($form['submission_date']) ?></td>
                                <td><?= htmlspecialchars($form['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        document.getElementById('semester').addEventListener('change', function () {
            const semester = this.value;
            const programId = document.getElementById('program').value;

            if (semester && programId) {
                fetch(`fetch_courses.php?semester=${semester}&program_id=${programId}`)
                    .then(response => response.json())
                    .then(data => {
                        const coursesSelect = document.getElementById('courses');
                        coursesSelect.innerHTML = '';

                        data.forEach(course => {
                            const option = document.createElement('option');
                            option.value = course.course_id;
                            option.textContent = course.course_name;
                            coursesSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching courses:', error));
            }
        });
    </script>
</body>
</html>
