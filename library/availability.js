document.addEventListener("DOMContentLoaded", function () {
  const availabilityStatus = document
    .getElementById("availability-status")
    .textContent.trim();
  const reserveButton = document.getElementById("reserve-button");
  const messageElement = document.getElementById("availability-message");

  if (availabilityStatus === "Available") {
    reserveButton.disabled = false;
    messageElement.textContent = "";
  } else {
    reserveButton.disabled = true;
    messageElement.textContent = "Please wait for some more days.";
  }
});
function redirectToBorrow(bookId) {
  if (bookId > 0) {
    window.location.href = `borrowbooks.php?book_id=${bookId}`;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const availabilityStatus = document
    .getElementById("availability-status")
    .textContent.trim();
  const reserveButton = document.getElementById("reserve-button");
  const messageElement = document.getElementById("availability-message");

  if (availabilityStatus === "Available") {
    reserveButton.disabled = false;
    messageElement.textContent = "";
  } else {
    reserveButton.disabled = true;
    messageElement.textContent = "Please wait for some more days.";
  }
});

function reserveBook(bookId) {
  // Send a POST request to reserve the book
  const data = {
    book_id: bookId,
  };

  fetch("checkavailability.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data), // Send data as JSON
  })
    .then((response) => response.json())
    .then((result) => {
      alert(result.message); // Show response message
      // Optionally, you can also update the button state here based on success/failure
      if (result.status === "success") {
        document.getElementById("reserve-button").disabled = true;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to reserve the book.");
    });
}
