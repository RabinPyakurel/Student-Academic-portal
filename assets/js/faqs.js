document.querySelectorAll(".faq-item").forEach((item) => {
  const question = item.querySelector(".faq-question");

  // Toggle the FAQ item
  question.addEventListener("click", () => {
    const isActive = item.classList.contains("active");

    // Collapse all other FAQ items
    document.querySelectorAll(".faq-item").forEach((faq) => {
      faq.classList.remove("active");
    });

    // Toggle the current FAQ item
    if (!isActive) {
      item.classList.add("active");
    }
  });
});
function goBack() {
  window.history.back();
}
