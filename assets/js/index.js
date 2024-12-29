const track = document.querySelector('.carousel-track');
const items = document.querySelectorAll('.carousel-item');
const prevButton = document.getElementById('prev-btn');
const nextButton = document.getElementById('next-btn');

let currentIndex = 0;
let itemsPerView = 1;


const updateItemsPerView = () => {
  const screenWidth = window.innerWidth;

  if (screenWidth > 1200) {
    itemsPerView = 3;
  } else if (screenWidth > 768) {
    itemsPerView = 2;
  } else {
    itemsPerView = 1;
  }
};


const updateCarousel = () => {
  const itemWidth = items[0].offsetWidth + 32;
  track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
};


nextButton.addEventListener('click', () => {
  const maxIndex = items.length - itemsPerView;

  if (currentIndex < maxIndex) {
    currentIndex++;
  } else {
    currentIndex = 0;
  }
  updateCarousel();
});


prevButton.addEventListener('click', () => {
  if (currentIndex > 0) {
    currentIndex--;
  } else {
    currentIndex = items.length - itemsPerView;
  }
  updateCarousel();
});

window.addEventListener('resize', () => {
  updateItemsPerView();
  updateCarousel();
});


updateItemsPerView();
updateCarousel();

//redirect to register page
const register = document.getElementById('register');
register.addEventListener('click', () => {
  window.location.href = "authentication/sign-up.php";
});
//redirect to login page for admin
const login_admin = document.getElementById('login-admin');
login_admin.addEventListener('click', () => {
  window.location.href = "admin/login.htm";
});


const login_user = document.getElementById('login-user');

login_user.addEventListener('click', () => {
  window.location.href = "authentication/sign-in.php";
});