<?php
include '../backend/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billingId = $_POST['billing_id'];

    // Retrieve payment details from the database
    $query = "SELECT total_fee FROM billing WHERE billing_id = :billing_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':billing_id' => $billingId]);
    $billing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($billing) {
        $amount = $billing['total_fee'];
        $returnUrl = 'https://7f65-120-89-104-48.ngrok-free.app/account/fee.php'; // Success URL (must be valid and accessible)
        $cancelUrl = 'https://7f65-120-89-104-48.ngrok-free.app/account/fee.php'; // Cancel URL (same for failure)
    
        // Test Merchant Code
        $merchantCode = 'epay_payment'; 
        $paymentUrl = "https://uat.esewa.com.np/epay/main";
        
        // Prepare payment URL
        $paymentUrl .= "?amt=$amount";
        $paymentUrl .= "&pdc=0&psc=0&txAmt=0";
        $paymentUrl .= "&tAmt=$amount";
        $paymentUrl .= "&pid=$billingId";
        $paymentUrl .= "&scd=$merchantCode";
        $paymentUrl .= "&su=" . urlencode($returnUrl);
        $paymentUrl .= "&fu=" . urlencode($cancelUrl);
    
        // Log and return the URL
        error_log("Payment URL: $paymentUrl");
        echo json_encode(['payment_url' => $paymentUrl]);
    } else {
        echo json_encode(['error' => 'Invalid billing ID']);
    }
    
}
?>
