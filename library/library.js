function filterBooks(category) {
  console.log(`Category selected: ${category}`);

  fetch(`fetchbooksUser.php?category=${encodeURIComponent(category)}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      const categoryBooks = document.getElementById("category-books");
      categoryBooks.innerHTML = ""; // Clear current category books

      if (data.status === "success" && data.data.length > 0) {
        data.data.forEach((book) => {
          const bookCard = document.createElement("div");
          bookCard.classList.add("book-card");
          bookCard.innerHTML = `
                <img src="${book.book_image}" alt="${book.title}" class="book-img">
                <h3>${book.title}</h3>
                <p>Author: ${book.author}</p>
                <p>Category: ${book.category}</p>
                <a href="checkavailability.php?book_id=${book.book_id}">
                  <button class="action-button">
                  Check Availability</button>
                </a>
            `;
          categoryBooks.appendChild(bookCard);
        });
      } else {
        categoryBooks.innerHTML = "<p>No books found for this category.</p>";
      }
    })
    .catch((error) => {
      console.error("Error fetching books:", error);
      const categoryBooks = document.getElementById("category-books");
      categoryBooks.innerHTML =
        "<p>Failed to load books. Please try again later.</p>";
    });
}
