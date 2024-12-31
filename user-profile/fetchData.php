<?php
header('Content-Type: application/json');

include '../backend/db_connection.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    include '../not-found.htm';
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // First, check if emergency contact exists
    $query = "SELECT COUNT(*) FROM emergency_contact WHERE std_id = :user_id;";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $exists = $stmt->fetchColumn();

    // Construct the query based on emergency contact existence
    $baseQuery = "SELECT * FROM student 
                  NATURAL JOIN program 
                  NATURAL JOIN department ";
    if ($exists) {
        $baseQuery .= "NATURAL JOIN emergency_contact ";
    }
    $baseQuery .= "WHERE std_id = :user_id;";

    // Execute the final query
    $stmt = $pdo->prepare($baseQuery);
    $stmt->execute([':user_id' => $user_id]);
    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If data is found, store it in session and send back the response
    if ($response) {
        if (!isset($_SESSION['username'])) {
            $_SESSION['username'] = $response[0]['name'];
        }
        if (!isset($_SESSION['semester'])) {
            $_SESSION['semester'] = $response[0]['semester'];
        }
        if (!isset($_SESSION['email'])) {
            $_SESSION['email'] = $response[0]['email'];
        }
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
