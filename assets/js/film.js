// Films functionality
let currentCategory = 'full-films';
let carouselPositions = {
    'full-films': 0,
    'short-films': 0,
    'series': 0
};
let autoSlideIntervals = {};

// Initialize films section
document.addEventListener('DOMContentLoaded', function() {
    initializeCarousels();
    startAutoSlide();
});

function showCategory(category) {
    // Hide all galleries
    const galleries = document.querySelectorAll('.films-gallery');
    galleries.forEach(gallery => gallery.classList.remove('active'));
    
    // Show selected gallery
    document.getElementById(category).classList.add('active');
    
    // Update tab states
    const tabs = document.querySelectorAll('.category-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    currentCategory = category;
    
    // Restart auto-slide for new category
    stopAutoSlide();
    startAutoSlide();
}

function initializeCarousels() {
    const categories = ['full-films', 'short-films', 'series'];
    
    categories.forEach(category => {
        const wrapper = document.querySelector(`#${category} .film-cards-wrapper`);
        if (wrapper) {
            const cards = wrapper.children;
            if (cards.length > 0) {
                // Clone first few cards to end for infinite loop
                const cardsToClone = Math.min(3, cards.length);
                for (let i = 0; i < cardsToClone; i++) {
                    const clone = cards[i].cloneNode(true);
                    wrapper.appendChild(clone);
                }
            }
        }
    });
}

function nextFilm(category) {
    const wrapper = document.querySelector(`#${category} .film-cards-wrapper`);
    const cards = wrapper.children;
    const cardWidth = cards[0].offsetWidth + 32; // 32px for gap
    const maxPosition = -(cards.length - 3) * cardWidth;