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
        echo "<script>alert('Password reset email sent.')</script>";
    } else {
        echo "<script>alert('Failed to send email.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Request</title>
    <link rel="stylesheet" href="../assets/css/form.css">
    <style>
        body {
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            position: relative;
            width: clamp(320px, 90%, 430px);
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .close-button {
            position: absolute;
            top: -3px;
            right: -200px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #aaa;
            transition: color 0.3s;
        }

        .close-button:hover {
            color: #000;
        }

        form {
            margin: 0;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            background-color: var(--btn-color);
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }

        button:disabled {
            background-color: #ccc;
        }

        #loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="form-container" id="form-container">
        <button class="close-button" onclick="redirectToSignup()">×</button>
        <h1>Request Password Reset</h1>
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

        const redirectToSignup = () => {
            window.location.href = 'sign-up.php';
        };
    </script>
</body>
</html>
