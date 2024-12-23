document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("termsModal");
    const openModalButton = document.getElementById("openTermsModal");
    const closeModalButton = document.querySelector(".close-model-btn");


    openModalButton.onclick = function (event) {
        event.preventDefault();
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }


    closeModalButton.onclick = function () {
        modal.style.display = "none";
        document.body.style.overflow = "";
    }


    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.style.overflow = "";
        }
    }
});
