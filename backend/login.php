<?php
include 'db_connection.php';

session_start();

$user_input = $_POST['username'];
$password = $_POST['password'];

if (filter_var($user_input, FILTER_VALIDATE_EMAIL)) {
    
    $stmt = $pdo->prepare("SELECT u.user_id, u.password FROM user u 
                           LEFT JOIN student s ON u.user_id = s.std_id 
                           WHERE s.email = :input");
} else {
    
    $stmt = $pdo->prepare("SELECT u.user_id, u.password FROM user u 
                           LEFT JOIN student s ON u.user_id = s.std_id 
                           WHERE u.user_id = :input");
}

$stmt->bindParam(':input', $user_input);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['semester'] = $user['semester'];
    $_SESSION['user_id'] = $user['user_id'];
    echo 'login successful';
} else {
    echo "Invalid credentials";
    exit;
}
