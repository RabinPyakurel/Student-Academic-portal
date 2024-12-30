<?php
header('Content-Type: application/json');

// Example: Mock database connection and data
$exams = [
    ["subject" => "Math", "date" => "2024-01-10", "semester" => "3"],
    ["subject" => "Science", "date" => "2024-01-12", "semester" => "3"],
    ["subject" => "English", "date" => "2024-01-15", "semester" => "3"]
];

$results = [
    ["subject" => "Math", "marks" => 85],
    ["subject" => "Science", "marks" => 90],
    ["subject" => "English", "marks" => 88]
];

// Output data as JSON
echo json_encode([
    "exams" => $exams,
    "results" => $results
]);
?>
