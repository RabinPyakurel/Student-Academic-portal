<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get JSON input from the frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['email'], $data['subject'], $data['message'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$name = htmlspecialchars($data['name']);
$email = htmlspecialchars($data['email']);
$subject = htmlspecialchars($data['subject']);
$message = htmlspecialchars($data['message']);

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@gmail.com'; // Replace with your email
    $mail->Password = 'your_email_password'; // Replace with your email password (use app password if using Gmail)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email settings
    $mail->setFrom('your_email@gmail.com', 'Your Website Name');
    $mail->addAddress($email); // Send confirmation to the user
    $mail->addAddress('your_email@gmail.com'); // Send a copy to your email

    $mail->isHTML(true);
    $mail->Subject = "Thank you for contacting us: $subject";
    $mail->Body    = "<p>Hi $name,</p><p>Thank you for reaching out to us. We have received your message and will get back to you shortly.</p><p><strong>Your Message:</strong></p><p>$message</p><p>Best Regards,<br>Your Website Team</p>";

    // Send email
    $mail->send();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
}