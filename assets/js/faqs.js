// FAQ Modal Elements
const faqBtn = document.getElementById('faq-btn');
const faqModal = document.getElementById('faq-modal');
const faqCloseBtn = document.getElementById('faq-close-btn');

// Open FAQ Modal
faqBtn.addEventListener('click', () => {
  faqModal.style.display = faqModal.style.display === 'block' ? 'none' : 'block';
});

// Close FAQ Modal
faqCloseBtn.addEventListener('click', () => {
  faqModal.style.display = 'none';
});

// Close Modal on Outside Click
window.addEventListener('click', (event) => {
  if (!faqModal.contains(event.target) && event.target !== faqBtn) {
    faqModal.style.display = 'none';
  }
});
