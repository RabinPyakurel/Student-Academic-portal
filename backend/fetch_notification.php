<?php
require_once 'db_connection.php';
session_start();

$userId = $_SESSION['user_id'];  

$stmt = $pdo->prepare("
    SELECT * FROM notification 
    WHERE std_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($notifications);
?>
