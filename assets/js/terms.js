document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("termsModal");
    const openModalButton = document.getElementById("openTermsModal");
    const closeModalButton = document.querySelector(".close-btn");

    // Open the modal when the link is clicked
    openModalButton.onclick = function (event) {
        event.preventDefault(); // Prevent the default link behavior
        modal.style.display = "flex"; // Change to "flex" to enable centering
        document.body.style.overflow = "hidden"; // Prevent body scrolling
    }

    // Close the modal when the close button is clicked
    closeModalButton.onclick = function () {
        modal.style.display = "none";
        document.body.style.overflow = ""; // Restore body scrolling
    }

    // Close the modal when clicking anywhere outside of the modal
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.style.overflow = ""; // Restore body scrolling
        }
    }
});
