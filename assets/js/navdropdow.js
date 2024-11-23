document.addEventListener('DOMContentLoaded', function () {
  // Get the profile picture and sub-menu
  const profilePic = document.getElementById('profile-pic');
  const subMenu = document.getElementById('subMenu');

  // Function to toggle the visibility of the sub-menu
  function toggleSubMenu() {
    subMenu.classList.toggle('active');
  }

  // Add event listener to the profile picture to toggle the sub-menu on click
  profilePic.addEventListener('click', toggleSubMenu);

  // Close the sub-menu if clicked outside
  window.addEventListener('click', function (event) {
    if (!subMenu.contains(event.target) && !profilePic.contains(event.target)) {
      subMenu.classList.remove('active');
    }
  });
});
