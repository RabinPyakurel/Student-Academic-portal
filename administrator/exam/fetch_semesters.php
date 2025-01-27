<?php
// Database connection
include __DIR__ .'/../../backend/db_connection.php';


try {
   
    $program_id = $_POST['program_id'];

    // Query to fetch the semesters for the given program_id
    $query = "SELECT DISTINCT semester FROM course WHERE program_id = :program_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
    $stmt->execute();

    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate options for semester dropdown
    if ($semesters) {
        echo '<option value="">Select Semester</option>';
        foreach ($semesters as $semester) {
            echo '<option value="' . $semester['semester'] . '">' . $semester['semester'] . '</option>';
        }
    } else {
        echo '<option value="">No Semesters Available</option>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
