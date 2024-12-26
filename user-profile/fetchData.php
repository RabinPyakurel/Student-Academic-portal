<?php
header('Content-Type: application/json');

include '../backend/db_connection.php';

session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
$user_id = $_SESSION['user_id'];

try {
    $query = "SELECT COUNT(*) FROM emergency_contact WHERE std_id = :user_id;";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $query = "SELECT * 
                  FROM student 
                  NATURAL JOIN department 
                  NATURAL JOIN program 
                  NATURAL JOIN emergency_contact 
                  WHERE std_id = :user_id;";
    } else {
        $query = "SELECT * 
                  FROM student 
                  NATURAL JOIN program 
                  NATURAL JOIN department 
                  WHERE std_id = :user_id;";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $response = $stmt->fetchAll();

    if ($response) {
        echo json_encode([
            'success' => true,
            'data' => $response
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the given user ID'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch data',
        'error' => $e->getMessage()
    ]);
}
?>
