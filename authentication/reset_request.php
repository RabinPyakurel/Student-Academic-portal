<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../backend/db_connection.php'; // PDO connection

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "Invalid email address.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT std_id FROM student WHERE email = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    if ($result) {
       $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id=?");
       $stmt->execute([$result['std_id']]);
       $user = $stmt->fetch();
       if (!$user) {
            echo 'There is no user with this email.';
            exit;
       }
    } else {
        echo 'There is no user with this email.';
        exit;
    }

    $token = bin2hex(random_bytes(32));

    $stmt = $pdo->prepare("INSERT INTO password_reset_requests (email, token) VALUES (?, ?)");
    $stmt->execute([$email, $token]);

    require 'send_mail.php'; // PHPMailer setup
    $resetLink = "http://localhost:8000/authentication/reset_password.php?token=" . $token;

    $subject = "Password Reset Request";
    $body = "Click the link below to reset your password:\n\n$resetLink";

    if (sendMail($email, $subject, $body)) {
        echo "Password reset email sent.";
    } else {
        echo "Failed to send email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        #loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
        #form-container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div id="form-container">
        <form method="POST" id="reset-form">
            <label for="email">Enter your email:</label>
            <input type="email" name="email" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
    <img id="loader" src="../assets/images/loader.gif" alt="Loading...">

    <script>
        const form = document.getElementById('reset-form');
        const loader = document.getElementById('loader');
        const formContainer = document.getElementById('form-container');

        form.addEventListener('submit', () => {
            loader.style.display = 'block';
            formContainer.style.opacity = '0.5'; // Optional: Dim the form during the loading
        });
    </script>
</body>
</html>
