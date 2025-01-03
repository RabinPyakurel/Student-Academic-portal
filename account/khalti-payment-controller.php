<?php 
    include '../backend/db_connection.php';
    session_start();
    

    if (!isset($_SESSION['user_id'])) {
        die('User is not logged in.');
    }
    
    $user_id = $_SESSION['user_id']; 
    
   
    $billing_id = $_GET['purchase_order_id'] ?? null;
    $amount = $_GET['amount'] ?? null;
    $status = $_GET['status'] ?? null;
    $order_name = $_GET['purchase_order_name'] ?? null;
    
   
    if (!$billing_id || !$amount || !$status || !$order_name) {
        die('Missing required payment details.');
    }
    
  
    $payment_method = 'Khalti';
    
    if ($status == 'Completed' || $status == 200) {
        echo 'Payment completed<br>';

        $amount_in_rupees = $amount / 100;
        
        try {
            $sql = "UPDATE billing
                    SET amount_paid = COALESCE(amount_paid, 0) + :amount, payment_method = :payment_method
                    WHERE billing_id = :billing_id AND std_id = :user_id";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':amount', $amount_in_rupees);
            $stmt->bindParam(':payment_method', $payment_method);
            $stmt->bindParam(':billing_id', $billing_id);
            $stmt->bindParam(':user_id', $user_id);
           
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo 'Billing information updated successfully.';
                header("Location:fee.php");
                exit();
            } else {
                echo 'No records updated.';
            }
        } catch (Exception $e) {
            echo 'Error updating billing: ' . $e->getMessage();
        }
    } elseif($status == 'User canceled' || $status == 400) {
        echo "<script>alert('Payment cancelled')</script>";
        header("Location:fee.php");
        exit();
    }
?>
