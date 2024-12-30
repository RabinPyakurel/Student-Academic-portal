<?php
include "../db_connection.php";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Print POST data for debugging
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $admission_no = $_POST['admission_no']; // Using admission_no
    $semester = $_POST['semester'];
    $program_id = $_POST['program_id'];
    $enrollment_year = $_POST['enrollment_year'];
    $dob = $_POST['dob'];
    $personal_email = $_POST['personal_email'];
    $contact_number = $_POST['contact_number'];   

    $query = "INSERT INTO student (name, email, std_id, semester, program_id, enrollment_year, dob, personal_email, contact_number) 
    VALUES ('$name', '$email', '$admission_no', '$semester', '$program_id', '$enrollment_year', '$dob', '$personal_email', '$contact_number')";


    if ($connection->query($query) === TRUE) {
        echo "<script>
        alert('New Student added successfully!');
        window.location.href = './adminDashboard/student_list.php;  
        </script>";
    } else {
        echo "Error: " . $query . "<br>" . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="./addstd.css">  <!-- Ensure correct path -->
</head>
<body>

    
    
    <div class="form-container">
        <h1>Add New Student</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">College Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="admission_no">Admission No:</label>
                <input type="text" id="admission_no" name="admission_no" required>
            </div>

            <div class="form-group">
                <label for="semester">Semester:</label>
                <select id="semester" name="semester" required>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                    <option value="3">3rd Semester</option>
                    <option value="4">4th Semester</option>
                </select>
            </div>

            <div class="form-group">
                <label for="program_id">Program ID:</label>
                <input type="text" id="program_id" name="program_id" required>
            </div>

            <div class="form-group">
                <label for="enrollment_year">Enrollment Year:</label>
                <input type="number" id="enrollment_year" name="enrollment_year" required>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>

            <div class="form-group">
                <label for="personal_email">Personal Email:</label>
                <input type="email" id="personal_email" name="personal_email" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>

            <button type="submit">Add Student</button>
        </form>
    </div>
</body>
</html>
