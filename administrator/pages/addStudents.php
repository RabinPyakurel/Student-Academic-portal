<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sapo", 'root', 'rabin');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch departments
$departments = $pdo->query("SELECT * FROM department")->fetchAll(PDO::FETCH_ASSOC);

// Fetch programs
$programs = $pdo->query("SELECT * FROM program")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $std_id = $_POST['std_id'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $semester = $_POST['semester'];
    $program_id = $_POST['program_id'];
    $enrollment_year = $_POST['enrollment_year'];

    // Check if student already exists
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM student WHERE std_id = ? OR email = ?");
    $checkStmt->execute([$std_id, $email]);
    $exists = $checkStmt->fetchColumn();

    if ($exists) {
        $message = "Student with this ID or Email already exists!";
    } else {
        // Proceed with insertion
        $stmt = $pdo->prepare("INSERT INTO student (std_id, email, name, semester, program_id, enrollment_year) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$std_id, $email, $name, $semester, $program_id, $enrollment_year])) {
            $message = "Student added successfully!";
        } else {
            $message = "Failed to add student.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="addStudent.css">
    <style>

.container {
    max-width: 600px;
    margin: 50px auto;
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    color: black;
    font-weight: bold;
    margin-bottom: 5px;
}

input,
select,
button {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    background-color: #007BFF;
    color: white;
    cursor: pointer;
    border: none;
}

button:hover {
    background-color: #0056b3;
}

.message {
    color: red;
    text-align: center;
}
input.error {
    border-color: red;
}

.validation-message {
    color: red;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

    </style>
</head>
<body>
    <?php include '../sidebar.htm' ?>
    <main>
    <div class="container">
        <h1>Add Student</h1>
        <form method="post" id="studentForm">
            <div class="form-group">
                <label for="department">Department:</label>
                <select id="department" name="department" onchange="filterPrograms()" required>
                    <option value="" disabled selected>Choose Department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['dept_id']; ?>"><?= htmlspecialchars($dept['dept_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="program">Program:</label>
                <select id="program" name="program_id" required>
                    <option value="" disabled selected>Choose Program</option>
                    <?php foreach ($programs as $program): ?>
                        <option value="<?= $program['program_id']; ?>" data-dept="<?= $program['dept_id']; ?>">
                            <?= htmlspecialchars($program['program_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="student-form" style="display: none;">
                <div class="form-group">
                    <label for="std_id">Student ID:</label>
                    <input type="text" id="std_id" name="std_id" required>
                    <span class="validation-message"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="firstnamelastname.enroll_year@kathford.edu.np" required>
                    <span class="validation-message"></span>
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <span class="validation-message"></span>
                </div>

                <div class="form-group">
                    <label for="semester">Semester:</label>
                    <input type="text" id="semester" name="semester" placeholder="e.g., First" required>
                    <span class="validation-message"></span>
                </div>

                <div class="form-group">
                    <label for="enrollment_year">Enrollment Year:</label>
                    <input type="text" id="enrollment_year" name="enrollment_year" placeholder="e.g., 2078" required>
                    <span class="validation-message"></span>
                </div>

                <button type="submit" name="add_student">Add Student</button>
            </div>
        </form>

        <p class="message"><?= htmlspecialchars($message); ?></p>
    </div>
    </main>
    <script>
        function filterPrograms() {
            const departmentId = document.getElementById('department').value;
            const programSelect = document.getElementById('program');
            const options = programSelect.querySelectorAll('option');
            programSelect.value = '';

            options.forEach(option => {
                const deptId = option.getAttribute('data-dept');
                if (!deptId || deptId === departmentId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            const studentForm = document.getElementById('student-form');
            studentForm.style.display = departmentId ? 'block' : 'none';
        }

        function validateField(field, regex, errorMessage) {
            const value = field.value.trim();
            const messageElement = field.nextElementSibling;

            if (regex.test(value)) {
                messageElement.textContent = ''; // Clear error message
                field.classList.remove('error');
                return true;
            } else {
                messageElement.textContent = errorMessage;
                field.classList.add('error');
                return false;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const stdIdField = document.getElementById('std_id');
            const emailField = document.getElementById('email');
            const semesterField = document.getElementById('semester');
            const enrollmentYearField = document.getElementById('enrollment_year');

            stdIdField.addEventListener('input', () => {
                validateField(stdIdField, /^\d+$/, 'Student ID must be numeric.');
            });

            emailField.addEventListener('input', () => {
    validateField(
        emailField, 
        /^[a-z]+[a-z]+\.\d{3}@kathford\.edu\.np$/i, 
        'Email must follow the format: firstnamelastname.enroll_year@kathford.edu.np.'
    );
});


            semesterField.addEventListener('input', () => {
                validateField(semesterField, /^(First|Second|Third|Fourth|Fifth|Sixth|Seventh|Eighth)$/i, 
                    'Semester must be in words (e.g., First, Second).');
            });

            enrollmentYearField.addEventListener('input', () => {
                validateField(enrollmentYearField, /^20[0-9]{2}$/, 
                    'Enrollment year must be in BS format (e.g., 2078).');
            });

            const form = document.getElementById('studentForm');
            form.addEventListener('submit', (event) => {
                const isValidStdId = validateField(stdIdField, /^\d+$/, 'Student ID must be numeric.');
                const isValidEmail = validateField(emailField, /^[a-z]+[a-z]+\.\d{3}@kathford\.edu\.np$/i, 
                    'Email must follow the format: firstnamelastname.079@kathford.edu.np.');
                const isValidSemester = validateField(semesterField, /^(First|Second|Third|Fourth|Fifth|Sixth|Seventh|Eighth)$/i, 
                    'Semester must be in words (e.g., First, Second).');
                const isValidEnrollmentYear = validateField(enrollmentYearField, /^20[0-9]{2}$/, 
                    'Enrollment year must be in BS format (e.g., 2078).');

                if (!isValidStdId || !isValidEmail || !isValidSemester || !isValidEnrollmentYear) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
