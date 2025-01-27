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

// document
//   .getElementById("contact-form")
//   .addEventListener("submit", function (event) {
//     event.preventDefault(); // Prevent default form submission

//     // Collect form data
//     const formData = {
//       name: document.getElementById("name").value,
//       email: document.getElementById("email").value,
//       subject: document.getElementById("subject").value,
//       message: document.getElementById("message").value,
//     };

//   // Send form data to the backend via fetch API
//   fetch("contactus.php", {
//     method: "POST",
//     headers: { "Content-Type": "application/json" },
//     body: JSON.stringify(formData),
//   })
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.success) {
//         alert(
//           "Your message has been submitted successfully! We will get back to you soon."
//         );
//         document.getElementById("contact-form").reset();
//       } else {
//         alert("Something went wrong. Please try again.");
//       }
//     })
//     .catch((error) => {
//       console.error("Error:", error);
//       alert("Failed to submit the form. Please try again later.");
//     });
// });
