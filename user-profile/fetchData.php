<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sapo", 'root', 'rabin');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]);
    exit;
}

$user_id = 5639;

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
