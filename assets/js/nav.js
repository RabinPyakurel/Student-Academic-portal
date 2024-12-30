document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');
    const profileDropdown = document.getElementById('profile-dropdown');
    const myProfileSelector = 'li.nav-item.my-profile';
    const logoutSelector = 'li.nav-item.logout';
    let dynamicItemsAdded = false;

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');

        // Add dynamic items only if the profileDropdown exists
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

    window.addEventListener('resize', () => {
        const isWideScreen = window.innerWidth > 813;
        if (isWideScreen) {
            navLinks.classList.remove('active');
            removeDynamicItems();
            if (profileDropdown) {
                profileDropdown.style.display = "block";
            }
            dynamicItemsAdded = false;
        } else if (profileDropdown) {
            profileDropdown.style.display = "none";
        }
    });
});
