<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Billing</title>
    <link rel="stylesheet" href="../assets/css/fee.css">
</head>

<body>
    <?php include '../layout/nav.htm' ?>
    <main>
    <div class="container">

        <div class="header">
            <h1>Fee / 632544</h1>
            <div class="status">
                <span>Status</span>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="profile-pic">
                <div class="image-placeholder">Photo</div>
            </div>
            <div class="details">
                <p><strong>Admission No :</strong> 632544</p>
                <p><strong>Username:</strong> Username Surname</p>
                <p><strong>Email:</strong> usernamesurname.2081@collegename.edu.np</p>
                <p><strong>Total fee amount:</strong> 650000</p>
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>First</td>
                        <td>150000</td>
                        <td>150000</td>
                        <td>Paid</td>
                        <td>Cheque</td>
                        <td>2024-09-11</td>
                    </tr>
                    <tr>
                        <td>Second</td>
                        <td>28000</td>
                        <td>28000</td>
                        <td>Paid</td>
                        <td>Bank Transfer</td>
                        <td>2024-09-11</td>
                    </tr>
                    <tr>
                        <td>Third</td>
                        <td>134300</td>
                        <td>130000</td>
                        <td>Partially Paid</td>
                        <td>Fone Pay</td>
                        <td>2024-09-11</td>
                    </tr>
                    <tr>
                        <td>Fourth</td>
                        <td>23400</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Button -->
        <div class="pay-now">
            <button>Pay Now</button>
        </div>
    </div>
    </main>
    <?php include '../layout/footer.htm>' ?>
</body>

</html>