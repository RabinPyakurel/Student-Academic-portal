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
       $stmt = $pdo->prepare("select * from user where user_id=?");
       $stmt->execute([$result['std_id']]);
       $user = $stmt->fetch();
       if(!$user){
            echo 'there is no user with this email';
            exit;
       }
    }else{
        echo 'there is no user with this email';
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
<form method="POST">
    <label for="email">Enter your email:</label>
    <input type="email" name="email" required>
    <button type="submit">Reset Password</button>
</form>
