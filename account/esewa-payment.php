<?php
include '../backend/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billingId = $_POST['billing_id'];

    // Retrieve payment details from the database
    $query = "SELECT total_fee - COALESCE(amount_paid,0) as payable_fee FROM billing WHERE billing_id = :bill_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':bill_id' => $billingId]);
    $billing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($billing) {
        $amount = $billing['payable_fee'];
        $returnUrl = 'http://localhost:8000/account/success.php'; // Success URL
        $cancelUrl = 'http://localhost:8000/account/cancel.php'; // Cancel URL
    
        // Test Merchant Code
        $merchantCode = 'epay_payment'; 
        $paymentUrl = "https://uat.esewa.com.np/epay/main";
        
        // Generate unique pid
        $timestamp = date('YmdHis'); // Timestamp for uniqueness
        $pid = "BILL-$billingId-$timestamp"; // Unique `pid` with billing_id embedded
        
        // Prepare payment URL
        $paymentUrl .= "?amt=$amount";
        $paymentUrl .= "&pdc=0&psc=0&txAmt=0";
        $paymentUrl .= "&tAmt=$amount";
        $paymentUrl .= "&pid=$pid";
        $paymentUrl .= "&scd=$merchantCode";
        $paymentUrl .= "&su=" . urlencode($returnUrl);
        $paymentUrl .= "&fu=" . urlencode($cancelUrl);
    
        // Log the URL for debugging
        error_log("Payment URL: $paymentUrl");

        // Return the payment URL
        echo json_encode(['payment_url' => $paymentUrl]);
    } else {
        echo json_encode(['error' => 'Invalid billing ID']);
    }
}
?>
