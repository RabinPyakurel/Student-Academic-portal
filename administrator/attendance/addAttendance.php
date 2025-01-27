<?php
include __DIR__ .'/../../backend/db_connection.php';

// Fetch program names to populate the program dropdown
$query = "SELECT program_id, program_name FROM program";
$stmt = $pdo->query($query);
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch semesters based on program_id dynamically
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get program_id and semester from form
    $program_id = $_POST['program_id'];
    $semester = $_POST['semester'];
    $attendance_date = $_POST['attendance_date'];

    // Fetch students based on the selected program and semester
    $stmt_students = $pdo->prepare("SELECT std_id, name FROM student WHERE program_id = :program_id AND semester = :semester");
    $stmt_students->execute(['program_id' => $program_id, 'semester' => $semester]);
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

    // Handle attendance marking
    if (isset($_POST['attendance'])) {
        $attendance = $_POST['attendance']; // This will be an array with std_id => status

        // Check if attendance for the selected date and student already exists
        $pdo->beginTransaction();
        try {
            foreach ($attendance as $std_id => $status) {
                // Check if attendance already exists for the student on this date
                $check_query = "SELECT attendance_id FROM attendance WHERE std_id = :std_id AND date = :date";
                $check_stmt = $pdo->prepare($check_query);
                $check_stmt->execute(['std_id' => $std_id, 'date' => $attendance_date]);

                if ($check_stmt->rowCount() == 0) {
                    // Insert attendance if not already marked
                    $insert_query = "INSERT INTO attendance (std_id, semester, date, status) VALUES (:std_id, :semester, :date, :status)";
                    $insert_stmt = $pdo->prepare($insert_query);
                    $insert_stmt->execute([
                        'std_id' => $std_id,
                        'semester' => $semester,
                        'date' => $attendance_date,
                        'status' => $status
                    ]);
                }else{
                   echo "<script>alert('already mark attendance for students in selected date')</script>";
                }
            }

            // Commit the transaction
            $pdo->commit();
            echo "Attendance marked successfully!";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Error marking attendance: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        form {
            width: 100%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        select, input {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .attendance-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .attendance-table th, .attendance-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ccc;
        }
        .attendance-table th {
            background-color: #f2f2f2;
        }
        .attendance-table input[type="checkbox"] {
            margin-right: 10px;
        }
        .attendance-table td .remarks {
            width: 200px;
        }
        .button-container button {
            width: auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .button-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Attendance form -->
<form method="POST" action="">
    <!-- Program Dropdown -->
    <label for="program_id">Program:</label>
    <select name="program_id" id="program_id" required>
        <option value="">Select Program</option>
        <?php foreach ($programs as $program) : ?>
            <option value="<?= $program['program_id']; ?>"><?= $program['program_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Semester Dropdown -->
    <label for="semester">Semester:</label>
    <select name="semester" id="semester" required>
        <option value="">Select Semester</option>
    </select>

    <!-- Attendance Date -->
    <label for="attendance_date">Attendance Date:</label>
    <input type="date" name="attendance_date" id="attendance_date" required>

    <!-- Button to fetch students and mark attendance -->
    <div class="button-container">
        <button type="submit">Fetch Students</button>
    </div>
</form>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($students)) : ?>
    <!-- Attendance Table for Students -->
    <form method="POST" action="">
        <input type="hidden" name="program_id" value="<?= $program_id; ?>">
        <input type="hidden" name="semester" value="<?= $semester; ?>">
        <input type="hidden" name="attendance_date" value="<?= $attendance_date; ?>">

        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) : ?>
                    <tr>
                        <td><?= $student['name']?></td>
                        <td><input type="radio" name="attendance[<?= $student['std_id']; ?>]" value="Present"></td>
                        <td><input type="radio" name="attendance[<?= $student['std_id']; ?>]" value="Absent"></td>
                        <td><input type="radio" name="attendance[<?= $student['std_id']; ?>]" value="Late"></td>
                        <td><input type="text" name="remarks[<?= $student['std_id']; ?>]" class="remarks"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="button-container">
            <button type="submit">Mark Attendance</button>
        </div>
    </form>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch semesters based on the selected program
        $('#program_id').change(function() {
            const program_id = $(this).val();
            if (program_id) {
                $.ajax({
                    url: '/administrator/exam/fetch_semesters.php',
                    method: 'POST',
                    data: { program_id },
                    success: function(data) {
                        $('#semester').html(data); // Populate the semester dropdown
                    }
                });
            } else {
                $('#semester').html('<option value="">Select Semester</option>');
            }
        });
    });
</script>

</body>
</html>
