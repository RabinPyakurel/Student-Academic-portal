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

  <section class="hero">
  <div class="hero-content">
    <h1>Get in Touch</h1>
    <p>We'd Love to Hear from You!</p>
    <button id="contact-button">Contact Us</button>
  </div>
</section>
<br><br>





  <!-- Modal Contact Form -->
  <div id="contact-modal" style="display:none;" >
    <div class="modal-content">
      <span class="close" onclick="document.getElementById('contact-modal').style.display='none';" >&times;</span>
      <h2>Contact Us</h2>
      <form id="contact-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required >

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required >

        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required ></textarea>

        <button type="submit">Submit</button>
      </form>
    </div>
  </div>

  <?php include "../layout/footer.htm"; ?>
  <script src="../assets/js/contact.js"></script>
</body>

</html>
