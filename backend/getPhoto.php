<?php
header('Content-Type: application/json');
include '../backend/db_connection.php';

session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
 $user_id = $_SESSION['user_id'];

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