// Project Slider Functions
let currentBillboard = 0;
const slides = document.querySelectorAll('.billboard-slide');
const dots = document.querySelectorAll('.slider-dots .dot');

function showBillboardSlide(n) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === n);
    });
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === n);
    });
    currentBillboard = n;
}

function nextSlide() {
    let n = (currentBillboard + 1) % slides.length;
    showBillboardSlide(n);
}

function previousSlide() {
    let n = (currentBillboard - 1 + slides.length) % slides.length;
    showBillboardSlide(n);
}

function currentSlide(n) {
    showBillboardSlide(n - 1);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => showBillboardSlide(0));

// Stats Animation
function animateStats() {
    const counters = document.querySelectorAll('.stat-number');
    counters.forEach(counter => {
        const target = counter.textContent.replace(/[^\d.]/g, '');
        const increment = target / 50;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {    <!-- Hero Section -->
    <section id="hero" class="hero section active">
        <div class="hero-content">
            <h1>SAADAN</h1>
            <p class="hero-tagline">Studio for Arts, Ambition, and Diversity Across Nation</p>
            <p>A globally-recognized digital hub for storytelling, talent, and artistic innovation</p>
            <div class="hero-buttons">
                <a href="#" class="btn btn-primary" onclick="showSection('artists')">Meet Our Artists</a>
                <a href="#" class="btn btn-secondary" onclick="showTrailer()">Watch Trailer</a>
                <a href="#" class="btn btn-secondary" onclick="showSection('engagement')">Vote Now</a>
            </div>
        </div>
        
        <!-- Dashboard Content Below Hero -->
        <div class="dashboard-content">
            <div class="container">
                
                <!-- Featured Projects Slider -->
                <div class="featured-section">
                    <h2 class="section-title" style="color: white; margin-bottom: 2rem;">Featured Projects</h2>
                    <div class="projects-slider">
                        <div class="project-slide active">
                            <div class="project-content">
                                <div class="project-image">
                                    <div class="project-placeholder">🎬</div>
                                    <div class="project-badge">Coming Soon</div>
                                </div>
                                <div class="project-info">
                                    <h3>Echoes of Tomorrow</h3>
                                    <p class="project-genre">Sci-Fi Drama • Feature Film</p>
                                    <p>A gripping tale of humanity's resilience in face of technological evolution. Starring emerging talent from diverse backgrounds.</p>
                                    <div class="project-stats">
                                        <span>🎭 Lead Cast: 6</span>
                                        <span>📅 Release: Q4 2025</span>
                                        <span>⭐ Pre-Production</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="project-slide">
                            <div class="project-content">
                                <div class="project-image">
                                    <div class="project-placeholder">📺</div>
                                    <div class="project-badge">In Production</div>
                                </div>
                                <div class="project-info">
                                    <h3>Community Stories</h3>
                                    <p class="project-genre">Drama Series • Web Series</p>
                                    <p>An anthology celebrating the untold stories of diverse communities across the nation.</p>
                                    <div class="project-stats">
                                        <span>📺 Episodes: 8</span>
                                        <span>📅 Premiere: Aug 2025</span>
                                        <span>⭐ Filming</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="project-slide">
                            <div class="project-content">
                                <div class="project-image">
                                    <div class="project-placeholder">🎵</div>
                                    <div class="project-badge">Development</div>
                                </div>
                                <div class="project-info">
                                    <h3>Rhythm & Dreams</h3>
                                    <p class="project-genre">Musical • Short Film</p>
                                    <p>A musical journey exploring the power of art to bridge cultural divides.</p>
                                    <div class="project-stats">
                                        <span>🎵 Original Score</span>
                                        <span>📅 TBD 2025</span>
                                        <span>⭐ Pre-Production</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slider-controls">
                        <button class="slider-btn" onclick="previousSlide()">‹</button>
                        <div class="slider-dots">
                            <span class="dot active