function validateForm() {
  // Get form elements
  const title = document.getElementById("title");
  const author = document.getElementById("author");
  const publisher = document.getElementById("publisher");
  const yearPublished = document.getElementById("year_published");
  const availableCopies = document.getElementById("available_copies");
  const totalCopies = document.getElementById("total_copies");
  const category = document.getElementById("category");
  const bookImage = document.getElementById("book_image");

  // Validate Title (should not be empty)
  if (title.value.trim() === "") {
    alert("Book title is required.");
    title.focus();
    return false;
  }

  // Validate Author (should not be empty)
  if (author.value.trim() === "") {
    alert("Author is required.");
    author.focus();
    return false;
  }

  // Validate Publisher (should not be empty)
  if (publisher.value.trim() === "") {
    alert("Publisher is required.");
    publisher.focus();
    return false;
  }

  // Validate Year Published (should be a valid number and between a reasonable range)
  const yearPattern = /^[0-9]{4}$/;
  if (!yearPattern.test(yearPublished.value.trim())) {
    alert("Please enter a valid year.");
    yearPublished.focus();
    return false;
  }

  // Validate Available Copies (should be a number and greater than 0)
  if (isNaN(availableCopies.value.trim()) || availableCopies.value <= 0) {
    alert("Available copies must be a positive number.");
    availableCopies.focus();
    return false;
  }

  // Validate Total Copies (should be a number and greater than 0)
  if (isNaN(totalCopies.value.trim()) || totalCopies.value <= 0) {
    alert("Total copies must be a positive number.");
    totalCopies.focus();
    return false;
  }

  // Ensure Available Copies are not greater than Total Copies
  if (
    parseInt(availableCopies.value.trim()) > parseInt(totalCopies.value.trim())
  ) {
    alert("Available copies cannot be greater than total copies.");
    availableCopies.focus();
    return false;
  }

  // Validate Category (should not be empty)
  if (category.value.trim() === "") {
    alert("Category is required.");
    category.focus();
    return false;
  }

  // Validate Book Image (if a file is uploaded, check the file type)
  if (bookImage.files.length > 0) {
    const allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    const fileExtension = bookImage.files[0].name
      .split(".")
      .pop()
      .toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
      alert("Only JPG, JPEG, PNG, and GIF images are allowed.");
      bookImage.focus();
      return false;
    }
  }

  // If all validations pass, allow form submission
  return true;
}
