document.addEventListener("DOMContentLoaded", function () {
  const reserveButton = document.getElementById("reserve-button");

  if (reserveButton) {
    const bookId = reserveButton.getAttribute("data-book-id");

    if (!bookId) {
      console.error("No book ID found in data-book-id attribute.");
      return;
    }

    // Continue with your existing functionality
    const availabilityStatusElement = document.getElementById(
      "availability-status"
    );
    const messageElement = document.getElementById("availability-message");

    function updateAvailabilityStatus(availability) {
      if (availability === "Available") {
        reserveButton.disabled = false;
        messageElement.textContent = "";
      } else {
        reserveButton.disabled = true;
        messageElement.textContent = "Please wait for some more days.";
      }
    }

    function checkAvailability(bookId) {
      fetch(`checkavailability.php?book_id=${bookId}`)
        .then((response) => response.json())
        .then((data) => {
          console.log("Availability Check Response:", data);
          if (data.status === "success") {
            availabilityStatusElement.textContent = data.data.is_available;
            updateAvailabilityStatus(data.data.is_available);
          } else {
            messageElement.textContent = `Error: ${data.message}`;
          }
        })
        .catch((error) => {
          console.error("Error checking availability:", error);
        });
    }

    // Check availability if bookId is valid
    checkAvailability(bookId);

    function reserveBook(bookId) {
      console.log("Reserving Book ID:", bookId);
      fetch(`reserve.php?book_id=${bookId}`, {
        method: "GET",
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Reservation Response:", data);
          alert(data.message);
          if (data.status === "success") {
            reserveButton.disabled = true; // Disable button after reservation
          }
        })
        .catch((error) => {
          console.error("Error reserving book:", error);
          alert("An error occurred while reserving the book.");
        });
    }

    // Event listener for the reserve button
    reserveButton.addEventListener("click", function () {
      reserveBook(bookId);
    });
  }
});
