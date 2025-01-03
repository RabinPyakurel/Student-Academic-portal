<?php
 include '../backend/db_connection.php';
 session_start();
 

 if (!isset($_SESSION['user_id'])) {
     die('User is not logged in.');
 }
 
$user_id = $_SESSION['user_id']; 
 
$pid = $_GET['oid'];

$parts = explode('-', $pid);
$billing_id = $parts[1];
$amount = $_GET['amt'];
$payment_method = 'eSewa';

$sql = "UPDATE billing
SET amount_paid = COALESCE(amount_paid, 0) + :amount, payment_method = :payment_method
WHERE billing_id = :billing_id AND std_id = :user_id";
$stmt = $pdo->prepare($sql);

$stmt->execute([":amount"=>$amount,
                        ":payment_method"=>$payment_method,
                        ":billing_id"=>$billing_id,
                        ":user_id"=>$user_id]);

 if ($stmt->rowCount() > 0) {
    echo "<script>alert('Billing information updated successfully.')</script>";
    header("Location:fee.php");
    exit();
} else {
    echo 'No records updated.';
}