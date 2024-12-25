<?php
include '../backend/db_connection.php';

session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
 $user_id = $_SESSION['user_id'];

function fetchMonthlyAttendance($pdo,$user_id){
$query = "SELECT 
            DATE_FORMAT(date, '%M, %Y') AS month_year,
            ROUND((SUM(CASE WHEN status = 'Present' THEN 1 WHEN status = 'Late' THEN 0.5 ELSE 0 END)/COUNT(*))*100, 2) AS attendance_percentage
          FROM attendance
          WHERE std_id = :user_id
          GROUP BY YEAR(date), MONTH(date), DATE_FORMAT(date, '%M, %Y')
          ORDER BY YEAR(date) DESC, MONTH(date) DESC;";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$attendanceSummary = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($attendanceSummary);
}

function fetchOverallAttendance($pdo,$user_id){
$query = "SELECT
            ROUND((SUM(CASE WHEN status = 'Present' THEN 1 WHEN status = 'Late' THEN 0.5 ELSE 0 END)/COUNT(*))*100, 2) AS attendance_percentage
          FROM attendance
          WHERE std_id = :user_id;";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$overallSummary = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($overallSummary);
}

function fetchAttendanceDetails($pdo,$user_id,$monthYear){
    $query = "SELECT
                DATE_FORMAT(date,'%Y-%m-%d') AS date,
                status,
                remarks
                FROM attendance
                WHERE std_id = :user_id
                  AND DATE_FORMAT(date, '%M, %Y') = :month_year
                ORDER BY date;";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id',$user_id);
    $stmt->bindParam(':month_year',$monthYear,PDO::PARAM_STR);
    $stmt->execute();
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($details);
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
    if($action === 'overall'){
        fetchOverallAttendance($pdo,$user_id);
    }elseif($action === 'monthly'){
        fetchMonthlyAttendance($pdo,$user_id);
    }elseif($action === 'details' && isset($_GET['month_year'])){
        fetchAttendanceDetails($pdo,$user_id,$_GET['month_year']);
    }else{
        echo "Invalid action";
    }
}