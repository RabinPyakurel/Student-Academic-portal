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

$query = "select photo_url from student where std_id= :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id'=>$user_id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if($student&&$student['photo_url']){
    echo json_encode(['success'=>true,'url'=>$student['photo_url']]);
}else{
    echo json_encode(['success'=>false,'message'=>'No image found']);
}
?>