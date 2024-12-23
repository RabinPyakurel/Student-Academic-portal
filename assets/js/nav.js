
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', function (e) {

        const target = document.querySelector(this.getAttribute('href'));
        target.scrollIntoView({ behavior: 'smooth' });
    });
});

document.getElementById('profile-icon').addEventListener('click', function (e) {
    e.preventDefault();
    const dropdownMenu = document.getElementById('dropdown-menu');
    dropdownMenu.classList.toggle('show');

    e.stopPropagation();
});


document.addEventListener('click', function (e) {
    const dropdownMenu = document.getElementById('dropdown-menu');
    const profileIcon = document.getElementById('profile-icon');


    if (!profileIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove('show');
    }
});


document.getElementById('logout').addEventListener('click', function () {
    alert("Logged out successfully!");
    window.location.href = "/index.php";
});
