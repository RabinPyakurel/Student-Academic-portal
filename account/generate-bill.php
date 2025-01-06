<?php
require_once '../vendor/autoload.php'; 
include '../backend/db_connection.php';

use Dompdf\Dompdf;


session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['billing_id'])) {
    die('Unauthorized Access');
}

$user_id = $_SESSION['user_id'];
$billing_id = htmlspecialchars($_GET['billing_id']); // Sanitize input


$query = "SELECT b.billing_id, b.semester, b.total_fee, b.amount_paid, b.payment_status, b.payment_method, b.payment_date, b.billing_date,
                 s.name AS student_name, s.email
          FROM billing b
          JOIN student s ON b.std_id = s.std_id
          WHERE b.billing_id = :billing_id AND b.std_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':billing_id' => $billing_id, ':user_id' => $user_id]);
$billing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$billing) {
    die('Billing record not found');
}

$balance_due = $billing['total_fee'] - $billing['amount_paid'];
$remarks = $billing['payment_status'] === 'Unpaid'||'Partially Paid' 
           ? 'Please complete the balance payment at the earliest.' 
           : 'Thank you for your full payment.';


function generateHtml($billing, $balance_due, $remarks) {
    return "
        <style>
            body { font-family: Arial, sans-serif; }
            .receipt { font-size: 12px; }
            .header { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
            .details, .payment-details { margin-bottom: 20px; }
            .details td, .payment-details td { padding: 5px; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; }
            th { text-align: left; background-color: #f2f2f2; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; }
            .signature { margin-top: 50px; text-align: right; }
        </style>
        <div class='receipt'>
            <div class='header'>Student Fee Bill</div>
            <table class='details'>
                <tr>
                    <td><strong>Bill No:</strong> {$billing['billing_id']}</td>
                    <td><strong>Date:</strong> {$billing['billing_date']}</td>
                </tr>
                <tr>
                    <td><strong>Student Name:</strong> {$billing['student_name']}</td>
                    <td><strong>Email:</strong> {$billing['email']}</td>
                </tr>
                <tr>
                    <td><strong>Semester:</strong> {$billing['semester']}</td>
                </tr>
            </table>
            <table class='payment-details'>
                <tr>
                    <th>Payment Mode</th>
                    <td>{$billing['payment_method']}</td>
                </tr>
                <tr>
                    <th>Payment Date</th>
                    <td>{$billing['payment_date']}</td>
                </tr>
                <tr>
                    <th>Total Fee</th>
                    <td>{$billing['total_fee']}</td>
                </tr>
                <tr>
                    <th>Amount Paid</th>
                    <td>{$billing['amount_paid']}</td>
                </tr>
                <tr>
                    <th>Balance Due</th>
                    <td>{$balance_due}</td>
                </tr>
            </table>
            <div class='remarks'><strong>Remarks:</strong> {$remarks}</div>
            <div class='signature'>Authorized Signature</div>
        </div>
    ";
}


$dompdf = new Dompdf();
$html = generateHtml($billing, $balance_due, $remarks);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();


$dompdf->stream('bill_' . $billing_id . '.pdf', ['Attachment' => 1]); // 1 = Force download
?>
