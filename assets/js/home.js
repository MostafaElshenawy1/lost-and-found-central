// Home page functionality
document.addEventListener('DOMContentLoaded', function () {
  // Initialize slider functionality
  initializeSliders();

  // Animate statistics
  animateStats();

  // Update statistics with actual counts
  updateStatistics();
});

function initializeSliders() {
  const sliders = document.querySelectorAll('.slider');

  sliders.forEach((slider) => {
    const sliderId = slider.id;
    const leftArrow = document.querySelector(
      `[data-slider="${sliderId}"].left`
    );
    const rightArrow = document.querySelector(
      `[data-slider="${sliderId}"].right`
    );

    if (leftArrow && rightArrow) {
      leftArrow.addEventListener('click', () => scrollSlider(slider, 'left'));
      rightArrow.addEventListener('click', () => scrollSlider(slider, 'right'));

      // Update arrow states
      updateArrowStates(slider, leftArrow, rightArrow);

      // Listen for scroll events to update arrow states
      slider.addEventListener('scroll', () => {
        updateArrowStates(slider, leftArrow, rightArrow);
      });
    }
  });
}

function scrollSlider(slider, direction) {
  const slideWidth = slider.querySelector('.slide')?.offsetWidth || 240;
  const gap = 24; // 1.5rem gap
  const scrollAmount = slideWidth + gap;

  if (direction === 'left') {
    slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  } else {
    slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  }
}

function updateArrowStates(slider, leftArrow, rightArrow) {
  const isAtStart = slider.scrollLeft <= 0;
  const isAtEnd =
    slider.scrollLeft >= slider.scrollWidth - slider.clientWidth - 1;

  leftArrow.disabled = isAtStart;
  rightArrow.disabled = isAtEnd;
}

function animateStats() {
  const statNumbers = document.querySelectorAll('.stat-number');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const target = entry.target;
        const finalValue = parseInt(target.textContent) || 0;
        animateNumber(target, 0, finalValue, 1500);
        observer.unobserve(target);
      }
    });
  });

  statNumbers.forEach((stat) => observer.observe(stat));
}

function animateNumber(element, start, end, duration) {
  const startTime = performance.now();

  function update(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);

    // Easing function for smooth animation
    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
    const current = Math.floor(start + (end - start) * easeOutQuart);

    element.textContent = current;

    if (progress < 1) {
      requestAnimationFrame(update);
    } else {
      element.textContent = end;
    }
  }

  requestAnimationFrame(update);
}

function updateStatistics() {
  // Count items in sliders
  const lostSlider = document.getElementById('lost-slider');
  const foundSlider = document.getElementById('found-slider');

  if (lostSlider && foundSlider) {
    const lostCount = lostSlider.querySelectorAll('.slide').length;
    const foundCount = foundSlider.querySelectorAll('.slide').length;
    const totalCount = lostCount + foundCount;

    // Update the stat numbers
    document.getElementById('lost-count').textContent = lostCount;
    document.getElementById('found-count').textContent = foundCount;
    document.getElementById('total-count').textContent = totalCount;
  }
}

// Add empty state handling
function checkForEmptySliders() {
  const sliders = document.querySelectorAll('.slider');

  sliders.forEach((slider) => {
    const slides = slider.querySelectorAll('.slide');
    if (slides.length === 0) {
      const emptyState = createEmptyState(slider.id);
      slider.appendChild(emptyState);

      // Hide arrows for empty sliders
      const sliderId = slider.id;
      const arrows = document.querySelectorAll(`[data-slider="${sliderId}"]`);
      arrows.forEach((arrow) => (arrow.style.display = 'none'));
    }
  });
}

function createEmptyState(sliderId) {
  const emptyDiv = document.createElement('div');
  emptyDiv.className = 'empty-state';

  const isLostSlider = sliderId.includes('lost');
  const icon = isLostSlider ? '🔍' : '🎉';
  const text = isLostSlider ? 'No lost items yet' : 'No found items yet';
  const subtext = isLostSlider
    ? 'Be the first to post a lost item!'
    : 'Help someone by posting a found item!';

  emptyDiv.innerHTML = `
    <div class="empty-state-icon">${icon}</div>
    <div class="empty-state-text">${text}</div>
    <div class="empty-state-subtext">${subtext}</div>
  `;

  return emptyDiv;
}

// Initialize empty state check after DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  // Small delay to ensure content is loaded
  setTimeout(checkForEmptySliders, 100);
});
