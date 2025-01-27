<?php
include __DIR__ .'/../../backend/db_connection.php';

// Fetch program names to populate the program dropdown
$query = "SELECT program_id, program_name FROM program";
$stmt = $pdo->query($query);
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch exam types to populate the exam type dropdown
$query_exam_types = "SELECT id, name FROM exam_type";
$stmt_exam_types = $pdo->query($query_exam_types);
$exam_types = $stmt_exam_types->fetchAll(PDO::FETCH_ASSOC);

// If form is submitted, handle the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $program_id = $_POST['program_id'];
    $semester = $_POST['semester'];
    $exam_type = $_POST['exam_type'];
    $exam_data = $_POST['exam_data']; // Array of exam data with course_id and exam_date

    // Begin the transaction for bulk insert
    $pdo->beginTransaction();

    try {
        // Prepare the SQL query to insert multiple exams
        $sql = "INSERT INTO exam (semester, program_id, exam_type, exam_date, course_id)
                VALUES (:semester, :program_id, :exam_type, :exam_date, :course_id)";
        $stmt = $pdo->prepare($sql);

        // Loop through each course and insert the data
        foreach ($exam_data as $course_id => $exam_date) {
            if ($exam_date) { // Only insert if the exam_date is provided
                $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
                $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
                $stmt->bindParam(':exam_type', $exam_type, PDO::PARAM_INT);
                $stmt->bindParam(':exam_date', $exam_date, PDO::PARAM_STR);
                $stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
                $stmt->execute();
            }
        }

        // Commit the transaction
        $pdo->commit();

        echo "Exams added successfully!";
    } catch (Exception $e) {
        // Rollback the transaction if something fails
        $pdo->rollBack();
        echo "Failed to add exams: " . $e->getMessage();
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Exams in Bulk</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/manageForm.css">
</head>
<body>

<!-- Exam form -->
<form method="POST" action="">

    <!-- Program Dropdown -->
    <label for="program_id">Program:</label>
    <select name="program_id" id="program_id" required>
        <option value="">Select Program</option>
        <?php foreach ($programs as $program) : ?>
            <option value="<?= $program['program_id']; ?>"><?= $program['program_name']; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Semester Dropdown (populated dynamically) -->
    <label for="semester">Semester:</label>
    <select name="semester" id="semester" required>
        <option value="">Select Semester</option>
    </select>

    <!-- Exam Type Dropdown (populated dynamically) -->
    <label for="exam_type">Exam Type:</label>
    <select name="exam_type" id="exam_type" required>
        <option value="">Select Exam Type</option>
        <?php foreach ($exam_types as $exam_type) : ?>
            <option value="<?= $exam_type['id']; ?>"><?= $exam_type['name']; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Courses List (populated dynamically) -->
     
    <div id="courses_list">

    </div>

    <!-- Submit Button -->
    <button type="submit">Add Exams</button>

</form>

<!-- JavaScript for Dynamic Dropdowns and Calendar -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {

        // Update Semester Dropdown based on Program Selection
        $('#program_id').change(function () {
            var program_id = $(this).val();
            if (program_id) {
                $.ajax({
                    url: 'fetch_semesters.php',
                    method: 'POST',
                    data: { program_id: program_id },
                    success: function (data) {
                        $('#semester').html(data); // Populate the semester dropdown
                    }
                });
            } else {
                $('#semester').html('<option value="">Select Semester</option>');
            }
        });

        // Update Courses List based on Semester and Exam Type Selection
        $('#semester, #exam_type').change(function () {
            var program_id = $('#program_id').val();
            var semester = $('#semester').val();
            var exam_type = $('#exam_type').val();
            if (program_id && semester && exam_type) {
                $.ajax({
                    url: 'fetch_courses.php',
                    method: 'POST',
                    data: { program_id: program_id, semester: semester, exam_type: exam_type },
                    success: function (data) {
                        $('#courses_list').html(data); // Populate the courses list
                        // Apply date picker to each course's input field
                        $('.exam_date').datepicker({ dateFormat: 'yy-mm-dd' });
                    }
                });
            } else {
                $('#courses_list').html('');
            }
        });
    });
</script>

</body>
</html>
