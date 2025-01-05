function filterBooks(category) {
  console.log(`Category selected: ${category}`);
  fetch(`fetchbooksUser.php?category=${category}`)
    .then((response) => response.json())
    .then((data) => {
      const bookGrid = document.getElementById("book-grid");
      bookGrid.innerHTML = ""; // Clear current books

      if (data.length === 0) {
        bookGrid.innerHTML = "<p>No books found for this category.</p>";
      } else {
        data.forEach((book) => {
          const bookCard = document.createElement("div");
          bookCard.classList.add("book-card");
          bookCard.innerHTML = `
            <img src="${book.book_image}" alt="${book.title}">
            <h3>${book.title}</h3>
            <p>Author: ${book.author}</p>
            <button class="action-button">Borrow</button>
            <button class="action-button">Check Availability</button>
          `;
          bookGrid.appendChild(bookCard);
        });
      }
    })
    .catch((error) => console.error("Error fetching books:", error));
}
