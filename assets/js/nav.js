document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');
    const profileDropdown = document.getElementById('profile-dropdown');
    const staticNotification = document.getElementById('static-notification');
    const notificationDropdown = document.getElementById('notification-dropdown'); // Added notificationDropdown
    const myProfileSelector = 'li.nav-item.my-profile';
    const logoutSelector = 'li.nav-item.logout';
    let dynamicItemsAdded = false;

    // Function to handle initial visibility based on screen size
    function initializeDropdowns() {
        const isWideScreen = window.innerWidth > 963;

        if (isWideScreen) {
            if (profileDropdown) profileDropdown.style.display = "block";
            if (notificationDropdown) {
                notificationDropdown.style.display = "block"; // Ensure notification is shown
                staticNotification.style.display = "none";
            }
        } else {
            if (profileDropdown) profileDropdown.style.display = "none";
            if (notificationDropdown) {
                notificationDropdown.style.display = "none"; // Ensure notification is hidden
                staticNotification.style.display = "block";

            }
        }
    }

    // Run the initialization function on page load
    initializeDropdowns();

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');

        if (profileDropdown && navLinks.classList.contains('active') && !dynamicItemsAdded) {
            const myProfileItem = document.createElement('li');
            const logoutItem = document.createElement('li');

            myProfileItem.classList.add('nav-item', 'my-profile');
            logoutItem.classList.add('nav-item', 'logout');

            myProfileItem.innerHTML = `<a href="/user-profile/user.php">My Profile</a>`;
            logoutItem.innerHTML = `<a href="/backend/logout.php">Logout</a>`;

            navLinks.appendChild(myProfileItem);
            navLinks.appendChild(logoutItem);

            dynamicItemsAdded = true;
        } else if (!navLinks.classList.contains('active')) {
            removeDynamicItems();
            dynamicItemsAdded = false;
        }
    });

    // Function to remove dynamic items
    function removeDynamicItems() {
        const myProfileItem = document.querySelector(myProfileSelector);
        const logoutItem = document.querySelector(logoutSelector);

        if (myProfileItem) myProfileItem.remove();
        if (logoutItem) logoutItem.remove();
    }

    // Handle resize event for Profile and Notification Dropdowns
    window.addEventListener('resize', () => {
        initializeDropdowns();
        navLinks.classList.remove('active');
        removeDynamicItems();
        dynamicItemsAdded = false;
    });
});
