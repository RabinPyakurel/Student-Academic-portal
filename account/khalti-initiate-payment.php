<?php
include '../backend/db_connection.php';


session_start();

$user_id = $_SESSION['user_id'];

if (isset($_POST['billing_id'])) {
    $billing_id = $_POST['billing_id'];
}


$query = "SELECT name, email, contact_number FROM student WHERE std_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$query = "SELECT total_fee - COALESCE(amount_paid,0) as payable_fee FROM billing WHERE billing_id = :bill_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':bill_id' =>$billing_id]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);
$totalFee = $bill['payable_fee'];
$curl = curl_init();
$data = [
    "return_url" => "http://localhost:8000/account/khalti-payment-controller.php", // Local URL for testing
    "website_url" => "http://localhost:8000/account/fee.php",              // Local URL for testing
    "amount" => $totalFee * 100, //amount must be less than 1000 rupees (but here we nee to send amount in paisa so max amt = 99900)
    "purchase_order_id" => $billing_id, 
    "purchase_order_name" => "Khalti",
    "customer_info" => [
        "name" => $user['name'],
        "email" => $user['email'],
        "phone" => $user['contact_number']
    ]
];

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
       'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',
        'Content-Type: application/json',
    ],
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
    $responseData = json_decode($response, true);

    // Check if payment_url exists and return it
    if (isset($responseData['payment_url'])) {
        echo json_encode(['payment_url' => $responseData['payment_url']]);
    } else {
        echo json_encode(['error' => 'Payment URL not found in response.']);
    }
}

curl_close($curl);
