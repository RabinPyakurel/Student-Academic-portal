document.addEventListener('DOMContentLoaded', function () {
    const notificationIcon = document.getElementById('notification-icon');
    const notificationBadge = document.getElementById('notification-badge');
    const notificationMenu = document.getElementById('notification-menu');

    // Add Jiggle Effect if there are notifications
    if (notificationBadge && parseInt(notificationBadge.textContent) > 0) {
        notificationIcon.classList.add('jiggle');

        // Stop jiggle after 5 seconds
        setTimeout(() => {
            notificationIcon.classList.remove('jiggle');
        }, 5000);
    }

    // Toggle Notification Dropdown Menu
    notificationIcon.addEventListener('click', function (e) {
        e.preventDefault();
        notificationMenu.classList.toggle('show');
    });

    // Close Dropdown on Outside Click
    document.addEventListener('click', function (e) {
        if (!notificationIcon.contains(e.target) && !notificationMenu.contains(e.target)) {
            notificationMenu.classList.remove('show');
        }
    });
});
