<?php
require_once '../vendor/autoload.php'; // Adjust the path to autoload.php as needed
include '../backend/db_connection.php';

use Dompdf\Dompdf;


session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['billing_id'])) {
    die('Unauthorized Access');
}

$user_id = $_SESSION['user_id'];
$billing_id = $_GET['billing_id'];


$query = "SELECT b.billing_id, b.semester, b.total_fee, b.amount_paid, b.payment_status, b.payment_method, b.payment_date,
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


$dompdf = new Dompdf();


$html = '
<style>
    body { font-family: Arial, sans-serif; }
    .receipt { font-size: 12px; }
    .header { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
    .details, .payment-details { margin-bottom: 20px; }
    .details td, .payment-details td { padding: 5px; }
    .footer { margin-top: 30px; text-align: center; font-size: 12px; }
    .signature { margin-top: 50px; text-align: right; }
</style>
<div class="receipt">
    <div class="header">Student Fee Receipt</div>
    <table class="details" width="100%" border="0">
        <tr>
            <td><strong>Receipt No:</strong> ' . htmlspecialchars($billing['billing_id']) . '</td>
            <td><strong>Date:</strong> ' . htmlspecialchars($billing['payment_date'] ?? date('Y-m-d')) . '</td>
        </tr>
        <tr>
            <td><strong>Student Name:</strong> ' . htmlspecialchars($billing['student_name']) . '</td>
            <td><strong>Email:</strong> ' . htmlspecialchars($billing['email']) . '</td>
        </tr>
        <tr>
            <td><strong>Semester:</strong> ' . htmlspecialchars($billing['semester']) . '</td>
        </tr>
    </table>
    <table class="payment-details" width="100%" border="1" cellspacing="0">
        <tr>
            <td><strong>Payment Mode</strong></td>
            <td>' . htmlspecialchars($billing['payment_method']) . '</td>
        </tr>
        <tr>
            <td><strong>Payment Date</strong></td>
            <td>' . htmlspecialchars($billing['payment_date']) . '</td>
        </tr>
        <tr>
            <td><strong>Total Fee</strong></td>
            <td>' . htmlspecialchars($billing['total_fee']) . '</td>
        </tr>
        <tr>
            <td><strong>Amount Paid</strong></td>
            <td>' . htmlspecialchars($billing['amount_paid']) . '</td>
        </tr>
        <tr>
            <td><strong>Balance Due</strong></td>
            <td>' . htmlspecialchars($billing['total_fee'] - $billing['amount_paid']) . '</td>
        </tr>
    </table>
    <div class="remarks"><strong>Remarks:</strong> Payment for Semester Fees</div>
    <div class="signature">Authorized Signature</div>
    <div class="footer">Thank you for your payment!</div>
</div>
';


$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();


$dompdf->stream('receipt_' . $billing_id . '.pdf', ['Attachment' => 1]); // 1 = Force download
?>
