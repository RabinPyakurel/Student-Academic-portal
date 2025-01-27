<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <link rel="stylesheet" href="./contactform.css">
</head>

<body>
  <?php include "../layout/nav.htm"; ?>
  <main>
    <section class="hero">
      <div class="hero-content">
        <h1>Get in Touch</h1>
        <p>We'd Love to Hear from You!</p>
        <button id="contact-button">Contact Us</button>
      </div>
    </section>
    <br><br>
    <div id="contact-modal" style="display:none;">
      <div class="modal-content">
        <span class="close" onclick="document.getElementById('contact-modal').style.display='none';">&times;</span>
        <h2>Contact Us</h2>
        <form id="contact-form" method="POST">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required>

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>

          <label for="subject">Subject:</label>
          <input type="text" id="subject" name="subject" required>

          <label for="message">Message:</label>
          <textarea id="message" name="message" rows="5" required></textarea>

          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
  </main>
  <?php include "../layout/footer.htm"; ?>
  <script src="../assets/js/contact.js"></script>
</body>

</html>

<?php
use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Composer autoload



if (!isset($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$name = $_POST['name'];
$email = $_POST['email'];
$subject =$_POST['subject'];
$message = $_POST['message'];



    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'studentacademicportal@gmail.com'; // SMTP username
        $mail->Password = 'qfvc lzfk wfsc pfol'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('noreply@sapo.com', 'sapo');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Thank you for contacting us: $subject";
    $mail->Body    = "<p>Hi $name,</p><p>Thank you for reaching out to us. We have received your message and will get back to you shortly.</p><p><strong>Your Message:</strong></p><p>$message</p><p>Best Regards,<br>Your Website Team</p>";


        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }

?>