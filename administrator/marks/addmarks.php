<?php
include __DIR__ . '/../../backend/db_connection.php';

// Fetch exams to populate the dropdown
$query_exams = "
    SELECT 
        e.exam_id, 
        CONCAT(et.name, ' - ', e.semester, ' - ', c.course_name) AS exam_detail 
    FROM 
        exam e
    INNER JOIN 
        exam_type et ON e.exam_type = et.id
    INNER JOIN 
        course c ON e.course_id = c.course_id
    WHERE 
        e.status = 'Upcoming'
";

$stmt_exams = $pdo->query($query_exams);
$exams = $stmt_exams->fetchAll(PDO::FETCH_ASSOC);

// If form is submitted, process the data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_id = $_POST['exam_id'];
    $marks_data = $_POST['marks_data']; // Array of marks with student ID as key

    try {
        $pdo->beginTransaction();

        // Insert marks for each student
        $sql = "INSERT INTO marks (std_id, course_id, marks_obtained, exam_id)
                VALUES (:std_id, :course_id, :marks_obtained, :exam_id)
                ON DUPLICATE KEY UPDATE marks_obtained = :marks_obtained_update";
        $stmt = $pdo->prepare($sql);

        foreach ($marks_data as $std_id => $marks_obtained) {
            if (is_numeric($marks_obtained)) { // Only insert if marks are valid
                $stmt->bindParam(':std_id', $std_id, PDO::PARAM_INT);
                $stmt->bindParam(':course_id', $_POST['course_id'], PDO::PARAM_STR);
                $stmt->bindParam(':marks_obtained', $marks_obtained, PDO::PARAM_STR);
                $stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
                $stmt->bindParam(':marks_obtained_update', $marks_obtained, PDO::PARAM_STR);
                $stmt->execute();
            }
        }

        $pdo->commit();
        echo "Marks added/updated successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to add marks: " . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Marks</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 800px;
            margin: auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        select, input {
            width: 50%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h2>Add Marks for Students</h2>

<form method="POST" action="">
    <!-- Exam Dropdown -->
    <label for="exam_id">Exam:</label>
    <select name="exam_id" id="exam_id" required>
        <option value="">Select Exam</option>
        <?php foreach ($exams as $exam) : ?>
            <option value="<?= $exam['exam_id']; ?>"><?= $exam['exam_detail']; ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Course ID (hidden field) -->
    <input type="hidden" name="course_id" id="course_id">

    <!-- Students List -->
    <div id="students_list"></div>

    <!-- Submit Button -->
    <button type="submit">Submit Marks</button>
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        $('#exam_id').change(function () {
            var exam_id = $(this).val();
            if (exam_id) {
                $.ajax({
                    url: 'fetch_students_for_exam.php', // Backend to fetch students for the selected exam
                    method: 'POST',
                    data: { exam_id: exam_id },
                    success: function (data) {
                        $('#students_list').html(data);
                        $('#course_id').val(data.course_id); // Set course ID dynamically
                    }
                });
            } else {
                $('#students_list').html('');
            }
        });
    });
</script>

</body>
</html>
