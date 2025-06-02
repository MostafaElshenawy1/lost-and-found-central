// Modal logic
function showModal(id) {
  document.getElementById(id).classList.add('show');
}
function hideModal(id) {
  document.getElementById(id).classList.remove('show');
}

// Check if elements exist before adding event listeners
if (document.getElementById('post-lost-btn')) {
  document
    .getElementById('post-lost-btn')
    .addEventListener('click', () => showModal('lost-modal'));
}

if (document.getElementById('post-found-btn')) {
  document
    .getElementById('post-found-btn')
    .addEventListener('click', () => showModal('found-modal'));
}

document.querySelectorAll('.close').forEach((btn) => {
  btn.addEventListener('click', (e) => {
    const modalId = e.target.getAttribute('data-modal');
    hideModal(modalId);
  });
});

document.querySelectorAll('.modal').forEach((modal) => {
  modal.addEventListener('click', (e) => {
    if (e.target === modal) hideModal(modal.id);
  });
});
/*
// Handle form submissions
if (document.getElementById('lost-form')) {
  document.getElementById('lost-form').addEventListener('submit', (e) => {
    e.preventDefault();
    // Here you would normally process the form data
    alert('Lost item form submitted!');
    hideModal('lost-modal');
  });
}

if (document.getElementById('found-form')) {
  document.getElementById('found-form').addEventListener('submit', (e) => {
    e.preventDefault();
    // Here you would normally process the form data
    alert('Found item form submitted!');
    hideModal('found-modal');
  });
}
*/
