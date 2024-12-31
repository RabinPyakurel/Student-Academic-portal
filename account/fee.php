<?php
include '../backend/db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    include 'not-found.htm';
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student information
$query = "SELECT name, email, photo_url FROM student WHERE std_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch total fee
$query = "SELECT SUM(total_fee) AS grand_total FROM billing WHERE std_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$totalFee = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch billing information
$query = "SELECT billing_id, semester, total_fee, amount_paid, payment_status, payment_method, payment_date FROM billing WHERE std_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$bill = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Billing</title>
    <link rel="stylesheet" href="../assets/css/fee.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .payment-option {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .payment-option:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include '../layout/nav.htm'; ?>
    <main>
        <div class="container">
            <!-- Header Section -->
            <div class="header">
                <h1>Fee / <?= htmlspecialchars($user_id) ?></h1>
                <div class="status">
                    <span>Payment Mode</span>
                    <label class="switch">
                        <input type="checkbox" id="payment-mode-toggle">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <!-- Student Information -->
            <div class="student-info">
                <div class="profile-pic">
                    <img src="<?= htmlspecialchars($user['photo_url']) ?>" alt="Profile Picture">
                </div>
                <div class="details">
                    <p><strong>Student Id:</strong> <?= htmlspecialchars($user_id) ?></p>
                    <p><strong>Username:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Total Fee Amount:</strong> <?= htmlspecialchars($totalFee['grand_total'] ?? '0') ?></p>
                </div>
            </div>
            <button class="payment-due">Payment Due</button>

            <!-- Payment History Section -->
            <div class="payment-history">
                <h2>Payment History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Semester</th>
                            <th>Total Fee</th>
                            <th>Amount Paid</th>
                            <th>Payment Status</th>
                            <th>Method of Payment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bill)): ?>
                            <?php foreach ($bill as $data): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['semester']) ?></td>
                                    <td><?= htmlspecialchars($data['total_fee'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['amount_paid'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['payment_status'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['payment_method'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($data['payment_date'] ?? '-') ?></td>
                                    <td>
                                        <button class="view-btn" data-id="<?= $data['billing_id'] ?>">View</button>
                                        <?php if ($data['payment_status'] == 'Unpaid' || $data['payment_status'] == 'Partially Paid'): ?>
                                            <button class="pay-now-btn" data-id="<?= $data['billing_id'] ?>">Pay Now</button>
                                        <?php elseif ($data['payment_status'] == 'Paid'): ?>
                                            <button class="receipt-btn" data-id="<?= $data['billing_id'] ?>">Show Receipt</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No payment history found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Payment Method Selection -->
            <div id="payment-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <h2>Select Payment Method</h2>
                    <button class="payment-option" id="esewa-option">eSewa</button>
                    <button class="payment-option" id="khalti-option">Khalti</button>
                    <button class="payment-option" id="manual-option">Manual Payment</button>
                </div>
            </div>

            <!-- Global Pay Now Button -->
            <div class="pay-now">
                <button id="global-pay-now" disabled>Pay All Pending Fees</button>
            </div>
        </div>
    </main>
    <?php include '../layout/footer.htm'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paymentModeToggle = document.querySelector('#payment-mode-toggle');
            const payNowButtons = document.querySelectorAll('.pay-now-btn');
            const globalPayNow = document.querySelector('#global-pay-now');
            const viewButtons = document.querySelectorAll('.view-btn');
            const receiptButtons = document.querySelectorAll('.receipt-btn');
            const paymentModal = document.getElementById('payment-modal');
            const closeModal = document.querySelector('.close-btn');
            const esewaOption = document.getElementById('esewa-option');
            const khaltiOption = document.getElementById('khalti-option');
            const manualOption = document.getElementById('manual-option');

            // Toggle Pay Now Buttons based on Payment Mode
            function togglePaymentMode(isEnabled) {
                payNowButtons.forEach(button => {
                    if (button) {
                        button.disabled = !isEnabled;
                    }
                });
                globalPayNow.disabled = !isEnabled;
            }

            paymentModeToggle.addEventListener('change', (e) => {
                togglePaymentMode(e.target.checked);
            });

            // View Details
            viewButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const billingId = button.getAttribute('data-id');
                    alert(`Viewing details for billing ID: ${billingId}`);
                });
            });

            // Pay Now
            payNowButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const billingId = button.getAttribute('data-id');
                    paymentModal.style.display = 'block'; // Show the payment method modal
                    paymentModal.setAttribute('data-billing-id', billingId); // Store billing ID in modal
                });
            });

            // Handle Payment Option Click
            function handlePaymentOption(paymentMethod) {
    const billingId = paymentModal.getAttribute('data-billing-id');
    let url;

    if (paymentMethod === 'eSewa') {
        url = 'esewa-test.php'; // Endpoint for eSewa
    } else if (paymentMethod === 'Khalti') {
        url = 'khalti-initiate-payment.php'; // Endpoint for Khalti
    } else {
        alert('Invalid payment method selected.');
        return;
    }

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: { billing_id: billingId }, // Sending billing_id as POST data
        success: function(response) {
            if (response.payment_url) {
                alert('Redirecting to payment page...');
                window.location.href = response.payment_url; // Redirect to the returned URL
            } else {
                alert('Failed to initiate payment. Please try again.');
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error); // Handle AJAX errors
        }
    });
}


            esewaOption.addEventListener('click', () => handlePaymentOption('eSewa'));
            khaltiOption.addEventListener('click', () => handlePaymentOption('Khalti'));
            manualOption.addEventListener('click', () => handlePaymentOption('Manual Payment'));

            // Close Modal
            closeModal.addEventListener('click', () => {
                paymentModal.style.display = 'none';
            });

            // Global Pay Now
            globalPayNow.addEventListener('click', () => {
                if (paymentModeToggle.checked) {
                    const unpaidIds = Array.from(payNowButtons)
                        .filter(btn => !btn.disabled)
                        .map(btn => btn.getAttribute('data-id'));
                    alert(`Initiating bulk payment for IDs: ${unpaidIds.join(', ')}`);
                } else {
                    alert('Enable Payment Mode to proceed with bulk payment.');
                }
            });
        });
    </script>
</body>

</html>
