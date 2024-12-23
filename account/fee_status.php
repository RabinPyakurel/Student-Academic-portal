<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Status</title>
    <style>
        main {
            padding: 20px;
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 2px solid #cccccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .fee-summary {
            text-align: center;
            margin-bottom: 20px;
        }

        .fee-summary .user-photo {
            width: 120px;
            height: 120px;
            background-color: #cccccc;
            border-radius: 50%;
            margin: 20px auto;
            display: block;
        }

        .fee-summary h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .fee-summary .details {
            font-size: 14px;
            line-height: 1.6;
        }

        .status {
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #fce4e4;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            color: #ff4d4d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            text-align: left;
        }

        table th,
        table td {
            border: 1px solid #cccccc;
            padding: 10px;
        }

        table th {
            background-color: #f7f7f7;
            font-weight: bold;
        }

        table a {
            color: #0073e6;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }

        .pay-now {
            display: block;
            width: 120px;
            margin: 20px auto;
            padding: 10px;
            background-color: #0073e6;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 20px;
        }

        .pay-now:hover {
            background-color: #005bb5;
        }
        @media (max-width: 768px) {
            header nav a {
                font-size: 12px;
                margin: 0 5px;
            }

            .fee-summary h1 {
                font-size: 20px;
            }

            table th,
            table td {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
  <?php include '../layout/nav.htm' ?>
    <main>
        <div class="fee-summary">
            <div class="user-photo"></div>
            <h1>Fee Status</h1>
            <div class="details">
                <p>Admission No: 632544</p>
                <p>Username Surname</p>
                <p>usernamesurname.2081@collegename.edu.np</p>
                <p>Total fee amount: 650000</p>
            </div>
            <span class="status">Payment Due</span>
        </div>
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
        <a href="#" class="pay-now">Pay Now</a>
    </main>
   <?php include '../layout/footer.htm' ?>
</body>

</html>