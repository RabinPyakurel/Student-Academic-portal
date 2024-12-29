<?php
require '../backend/db_connection.php'; // PDO connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token
    $stmt = $pdo->prepare("SELECT email, created_at FROM password_reset_requests WHERE token = ?");
    $stmt->execute([$token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        echo "Invalid or expired token.";
        exit;
    }

    // Check token expiration (1 hour validity)
    $createdAt = strtotime($resetRequest['created_at']);
    if (time() - $createdAt > 3600) {
        echo "Token expired.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if ($newPassword !== $confirmPassword) {
            echo "Passwords do not match.";
            exit;
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password in the `user` table
        $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE user_id = (SELECT std_id FROM student WHERE email = ?)");
        $stmt->execute([$hashedPassword, $resetRequest['email']]);

        // Delete the reset request
        $stmt = $pdo->prepare("DELETE FROM password_reset_requests WHERE token = ?");
        $stmt->execute([$token]);

        echo "alert('Password reset successful.')";
        header("location:sign-in.php");
        exit;
    }
} else {
    echo "No token provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        #password-requirements {
            list-style: none;
            padding: 0;
        }

        #password-requirements li {
            color: red;
            font-size: 0.9rem;
        }

        #password-requirements li.valid {
            color: green;
        }

        #confirm-message {
            font-size: 0.9rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <button class="close-button" onclick="redirectToSignup()">×</button>
        <h1>Reset Password</h1>
        <form method="POST">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required oninput="validatePassword()">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required oninput="validateConfirmPassword()">
            <button type="submit" id="submit-button" disabled>Reset Password</button>
            <span id="confirm-message"></span>
            <ul id="password-requirements">
                <li id="length">At least 8 characters</li>
                <li id="uppercase">At least one uppercase letter</li>
                <li id="lowercase">At least one lowercase letter</li>
                <li id="number">At least one number</li>
                <li id="special">At least one special character (!@#$%^&*(), etc.)</li>
                <li id="sequential">No sequential characters (e.g., 123, abc)</li>
                <li id="repeated">No repeated characters sequentially</li>
            </ul>
        </form>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const submitButton = document.getElementById('submit-button');
        const requirements = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            lowercase: document.getElementById('lowercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special'),
            sequential: document.getElementById('sequential'),
            repeated: document.getElementById('repeated'),
        };
        const confirmMessage = document.getElementById('confirm-message');

        const validatePassword = () => {
            const value = password.value;
            requirements.length.classList.toggle('valid', value.length >= 8);
            requirements.uppercase.classList.toggle('valid', /[A-Z]/.test(value));
            requirements.lowercase.classList.toggle('valid', /[a-z]/.test(value));
            requirements.number.classList.toggle('valid', /[0-9]/.test(value));
            requirements.special.classList.toggle('valid', /[!@#$%^&*(),.?":{}|<>]/.test(value));
            requirements.sequential.classList.toggle('valid', !/(012|123|234|345|456|567|678|789|abc|bcd|cde|def|efg|fgh|ghi|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i.test(value));
            requirements.repeated.classList.toggle('valid', !/([a-zA-Z0-9!@#$%^&*(),.?":{}|<>])\1{1,}/.test(value));
            submitButton.disabled = !Object.values(requirements).every((li) => li.classList.contains('valid'));
        };

        const validateConfirmPassword = () => {
            if (confirmPassword.value === password.value) {
                confirmMessage.textContent = 'Passwords match.';
                confirmMessage.style.color = 'green';
            } else {
                confirmMessage.textContent = 'Passwords do not match.';
                confirmMessage.style.color = 'red';
            }
        };

        const redirectToSignup = () => {
            window.location.href = 'sign-up.php';
        };
    </script>
</body>
</html>
