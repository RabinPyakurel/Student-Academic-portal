// Get the profile image and dropdown menu elements
const profileContainer = document.getElementById("profileContainer");
const dropdownMenu = document.getElementById("dropdownMenu");

// Toggle the dropdown menu visibility on image click
profileContainer.addEventListener("click", function () {
  if (dropdownMenu.style.display === "block") {
    dropdownMenu.style.display = "none"; // Hide the menu
  } else {
    dropdownMenu.style.display = "block"; // Show the menu
  }
});

// Close the dropdown if the user clicks outside the menu
window.onclick = function (event) {
  if (
    !event.target.matches(".profile-img") &&
    !event.target.matches(".dropdown-icon") &&
    !event.target.matches("#profileContainer")
  ) {
    dropdownMenu.style.display = "none";
  }
};

// user profile
document.getElementById("photoUpload").addEventListener("change", function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();

    reader.onload = function (event) {
      document
        .getElementById("userImage")
        .setAttribute("src", event.target.result);
    };

    reader.readAsDataURL(file);
  }
});

document.getElementById("editBtn").addEventListener("click", function () {
  alert("Edit functionality is not implemented yet.");
});
