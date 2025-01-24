<?php
include "../backend/db_connection.php";

$semester = $_GET['semester'] ?? null;
$program_id = $_GET['program_id'] ?? null;

if ($semester && $program_id) {
    $stmt = $pdo->prepare("SELECT course_id, course_name FROM course 
                           WHERE semester = :semester AND program_id = :program_id");
    $stmt->execute([':semester' => $semester, ':program_id' => $program_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
