document
  .getElementById("contact-button")
  .addEventListener("click", function () {
    const formSection = document.getElementById("contact-modal");

    formSection.style.display = "block";

    const offsetTop =
      formSection.getBoundingClientRect().top + window.scrollY - 65; // Adjust the offset if needed
    window.scrollTo({
      top: offsetTop,
      behavior: "smooth",
    });
  });


// Select form and handle submission
document.getElementById('contact-form').addEventListener('submit', function(event) {
  event.preventDefault();  // Prevent default form submission

  // Collect form data
  const formData = {
    name: document.getElementById('name').value,
    email: document.getElementById('email').value,
    subject: document.getElementById('subject').value,
    message: document.getElementById('message').value,
  };

  
  let contactData = JSON.parse(localStorage.getItem('contactData')) || [];
  contactData.push(formData);
  localStorage.setItem('contactData', JSON.stringify(contactData));

 
  document.getElementById('contact-form').reset();

  alert('Your message has been submitted successfully!');

  window.location.href = 'contactus.php';
});
