// scripts.js

// Function to handle timeline scrolling
function scrollTimeline(direction) {
    const container = document.querySelector('.timeline-container');
    const scrollAmount = container.clientWidth * 0.8;
    container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}

// Function to handle carousel scrolling
function scrollCarousel(direction) {
    const container = document.querySelector('.carousel-container');
    const scrollAmount = container.clientWidth * 0.8;
    container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}

// Optional: Auto-scrolling carousel (if desired)
// setInterval(() => scrollCarousel(1), 3000);
