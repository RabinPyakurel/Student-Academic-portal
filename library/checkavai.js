function checkAvailability(bookId) {
    fetch(`checkavailability.php?book_id=${bookId}`)
        .then((response) => response.json())
        .then((data) => {
            const availabilityStatusElement = document.getElementById(`availability-status-${bookId}`);
            
            if (data.status === "success") {
                // Clear previous content
                availabilityStatusElement.innerHTML = '';
                
                // Create status message
                const statusMessage = document.createElement('p');
                statusMessage.textContent = `Status: ${data.data.is_available}`;
                statusMessage.className = data.data.is_available === "Available" ? 'available' : 'not-available';
                availabilityStatusElement.appendChild(statusMessage);
                
                // If copies are available, show number of copies and borrow button
                if (data.data.available_copies > 0) {
                    const copiesMessage = document.createElement('p');
                    copiesMessage.textContent = `Available copies: ${data.data.available_copies}`;
                    availabilityStatusElement.appendChild(copiesMessage);
                    
                    const borrowButton = document.createElement('button');
                    borrowButton.textContent = 'Borrow';
                    borrowButton.className = 'borrow-button';
                    borrowButton.onclick = () => borrowBook(bookId);
                    availabilityStatusElement.appendChild(borrowButton);
                }
            } else {
                availabilityStatusElement.innerHTML = `Error: ${data.message}`;
                availabilityStatusElement.className = 'not-available';
            }
        })
        .catch((error) => {
            console.error("Error checking availability:", error);
            const availabilityStatusElement = document.getElementById(`availability-status-${bookId}`);
            availabilityStatusElement.innerHTML = "Error checking availability. Please try again.";
            availabilityStatusElement.className = 'not-available';
        });
}

function borrowBook(bookId) {
    // You can implement the borrow functionality here
    // For example, make an API call to your borrow endpoint
    console.log(`Borrowing book with ID ${bookId}`);
    alert('Book borrowing process initiated!');
    
    // After successful borrow, you might want to refresh the availability status
    checkAvailability(bookId);
}

// Check availability on page load if there's a book ID
document.addEventListener('DOMContentLoaded', function() {
    const bookCard = document.querySelector('.book-card');
    if (bookCard) {
        const checkAvailabilityButton = bookCard.querySelector('button');
        if (checkAvailabilityButton) {
            checkAvailabilityButton.click();
        }
    }
});