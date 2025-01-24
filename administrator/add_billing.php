<?php
// Database connection details
$host = 'localhost';
$dbname = 'sapo';
$username = 'root';
$password = 'rabin'; // Change password if necessary

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Fetch form data
        $std_id = $_POST['std_id'];
        $billing_date = $_POST['billing_date'];
        $semester = $_POST['semester'];
        $total_fee = $_POST['total_fee'];
        $amount_paid = $_POST['amount_paid'] ?: 0.00;
        $payment_status = $_POST['payment_status'];
        $payment_method = $_POST['payment_method'] ?: null;
        $payment_date = $_POST['payment_date'] ?: null;

        // Insert data into the billing table
        $sql = "INSERT INTO billing (std_id, billing_date, semester, total_fee, amount_paid, payment_status, payment_method, payment_date) 
                VALUES (:std_id, :billing_date, :semester, :total_fee, :amount_paid, :payment_status, :payment_method, :payment_date)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':std_id' => $std_id,
            ':billing_date' => $billing_date,
            ':semester' => $semester,
            ':total_fee' => $total_fee,
            ':amount_paid' => $amount_paid,
            ':payment_status' => $payment_status,
            ':payment_method' => $payment_method,
            ':payment_date' => $payment_date,
        ]);

        echo "<div class='success'>Billing information added successfully!</div>";
    }
} catch (PDOException $e) {
    echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
}
?>
