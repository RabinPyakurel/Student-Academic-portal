<?php
include "/backend/db_connection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer autoloader

if (isset($_GET['std_id']) && isset($_GET['email']) && isset($_GET['name'])) {
    $studentEmail = $_GET['email'];
    $studentName = $_GET['name'];

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Replace with your email
        $mail->Password = 'your-email-password'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email setup
        $mail->setFrom('your-email@gmail.com', 'Library Admin'); // Sender email
        $mail->addAddress($studentEmail, $studentName); // Recipient email

        $mail->isHTML(true);
        $mail->Subject = 'Reminder: Borrowed Books Due';
        $mail->Body = "<p>Dear $studentName,</p>
                        <p>This is a reminder to return your borrowed books to the library on time.</p>
                        <p>Please check your borrowed book records or contact the library for more details.</p>
                        <p>Thank you.</p>";

        $mail->send();
        echo "Reminder sent successfully to $studentName ($studentEmail).";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request. Student ID, email, or name is missing.";
}
?>