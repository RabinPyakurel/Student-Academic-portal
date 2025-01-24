<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Billing Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .success, .error {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Billing Information</h2>
        <form action="add_billing.php" method="POST">
            <label for="std_id">Student ID:</label>
            <input type="number" id="std_id" name="std_id" required>

            <label for="billing_date">Billing Date:</label>
            <input type="date" id="billing_date" name="billing_date" required>

            <label for="semester">Semester:</label>
            <input type="text" id="semester" name="semester" required>

            <label for="total_fee">Total Fee:</label>
            <input type="number" step="0.01" id="total_fee" name="total_fee" required>

            <label for="amount_paid">Amount Paid:</label>
            <input type="number" step="0.01" id="amount_paid" name="amount_paid">

            <label for="payment_status">Payment Status:</label>
            <select id="payment_status" name="payment_status">
                <option value="Unpaid">Unpaid</option>
                <option value="Partial">Partial</option>
                <option value="Paid">Paid</option>
            </select>

            <label for="payment_method">Payment Method:</label>
            <input type="text" id="payment_method" name="payment_method">

            <label for="payment_date">Payment Date:</label>
            <input type="date" id="payment_date" name="payment_date">

            <button type="submit">Add Billing</button>
        </form>
    </div>
</body>
</html>
