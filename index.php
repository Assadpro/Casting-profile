<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saadan Film Company - Studio for Arts, Ambition, and Diversity Across Nation</title>
    <meta name="description" content="Saadan Film Company - A globally-recognized digital hub for storytelling, talent, and artistic innovation">
    <meta property="og:title" content="Saadan Film Company">
    <meta property="og:description" content="Studio for Arts, Ambition, and Diversity Across Nation">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css">
</head>
<body>
    <!-- Navigation -->
  <?php include 'header.php'; ?>

<!-- Live Billboard Section -->
<section class="section active">
    <div class="live-billboard">
        <div class="film-grain"></div>
        <div class="live-indicator">🔴 LIVE</div>
        <div class="billboard-content" id="billboardContent">
            <!-- populated by JS -->
        </div>
        <div class="progress-container"><div class="progress-bar" id="billboardProgress"></div></div>
        <div class="billboard-nav" id="billboardNav"></div>
    </div>
</section>

<!-- Hero Section -->
    <section id="hero" class="hero section active" style="width: 100vw; min-height: 350px; display: flex; align-items: stretch; justify-content: center; padding: 0; margin: 0; background: linear-gradient(135deg, var(--black) 0%, var(--gray) 100%);">
        <!-- Left: Logo fills half, no background -->
        <div class="hero-logo" style="flex: 1; display: flex; align-items: center; justify-content: center;">
            <img src="logo/saadanfilm-logo.png" alt="Saadan Film Logo"
                 style="width: 60%; max-width: 600px; height: auto; border-radius: 0; box-shadow: none; background: none; padding: 0;">
        </div>
        <!-- Right: Text and buttons -->
        <div class="hero-info" style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; min-width: 320px; text-align: left; padding: 4rem;">
            <p class="hero-tagline" style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--gold); font-weight: bold;">
                Studio for Arts, Ambition, and Diversity Across Nation
            </p>
            <p style="margin-bottom: 2.5rem; color: var(--white); opacity: 0.9; font-size: 1.3rem;">
                A globally-recognized digital hub for storytelling, talent, and artistic innovation
            </p>
            <div class="hero-buttons" style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary" onclick="showSection('artists')">Meet Our Artists</a>
                <a href="#" class="btn btn-secondary" onclick="showTrailer()">Watch Trailer</a>
                <a href="vote_for_award.php" class="btn btn-primary">Vote for Best Actor/Actress</a>
            </div>
        </div>
    </section>


<!-- Finished Projects Section -->
<section class="section active">
    <div class="container">
        <div class="finished-projects">
            <h2 class="section-title">Available Films & Series</h2>
            <div class="projects-grid"><!-- projects will be injected here by renderProjects() --></div>
        </div>
    </div>
</section>

<!-- Dashboard Content Below Hero -->
<div class="dashboard-content">
    <div class="container">

        <!-- Featured Projects Slider -->
        <div class="featured-section">
            <h2 class="section-title">Featured Projects</h2>
            <div class="projects-slider"><!-- slides injected by renderFeatured() --></div>
            <div class="slider-controls">
                <button class="slider-btn" onclick="previousSlide()">‹</button>
                <div class="slider-dots"><!-- dots optional, created by JS if needed --></div>
                <button class="slider-btn" onclick="nextSlide()">›</button>
            </div>
        </div>

        <!-- Live Stats Dashboard -->
        <div class="stats-dashboard">
            <h2 class="section-title">Live Studio Stats</h2>
            <div class="stats-grid"><!-- stat cards may be injected by renderStats(); keep markup empty if you want full dynamic rendering --></div>
        </div>

        <!-- News & Updates Feed -->
        <div class="news-feed">
            <h2 class="section-title">Latest Updates</h2>
            <div class="news-container"><!-- news injected by renderNews() --></div>
            <div class="news-controls">
                <button class="btn btn-secondary" onclick="loadMoreNews()">Load More Updates</button>
            </div>
        </div>

        <!-- Interactive Timeline -->
        <div class="timeline-section">
            <h2 class="section-title">Our Journey</h2>
            <div class="timeline"><!-- timeline injected by renderTimeline() --></div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="quick-actions">
            <h2 class="section-title">Quick Actions</h2>
            <div class="actions-grid"><!-- actions injected by renderQuickActions() --></div>
        </div>

    </div>
</div>

    <!-- Footer -->
   <?php include 'footer.php'; ?>
    <script>
        // Navigation functionality
        function showSection(sectionName) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Show selected section
            document.getElementById(sectionName).classList.add('active');
            
            // Close mobile menu if open
            document.querySelector('.nav-menu').classList.remove('active');
            
            // Scroll to top
            window.scrollTo(0, 0);
        }

        function toggleMenu() {
            document.querySelector('.nav-menu').classList.toggle('active');
        }

        /* Helpers */
function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"']/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
function escapeAttr(s){ return encodeURI(String(s||'')); }

/* Renderers */
function renderBillboard(slides) {
  const container = document.getElementById('billboardContent');
  const nav = document.getElementById('billboardNav');
  const progress = document.getElementById('billboardProgress');
  if(!container) return;
  container.innerHTML = '';
  nav.innerHTML = '';
  slides.forEach((s,i) => {
    const slide = document.createElement('div');
    slide.className = 'billboard-slide' + (i===0 ? ' active' : '');
    if(s.media_type === 'video' && s.media_src) {
      slide.innerHTML = `<video class="billboard-video" ${i===0 ? 'autoplay' : ''} muted loop playsinline>
        <source src="${escapeAttr(s.media_src)}" type="video/mp4">
      </video>`;
    } else if(s.media_type === 'image' && s.media_src) {
      slide.innerHTML = `<div class="video-placeholder" style="background-image:url('${escapeAttr(s.media_src)}'); background-size:cover;"></div>`;
    } else {
      slide.innerHTML = `<div class="video-placeholder"><div class="overlay-icon">🎬</div></div>`;
    }
    slide.innerHTML += `<div class="billboard-overlay">
      <h2 class="billboard-title">${escapeHtml(s.title)}</h2>
      <p class="billboard-subtitle">${escapeHtml(s.subtitle || '')}</p>
      ${s.action_text ? `<a class="billboard-action" href="${escapeAttr(s.action_url || '#')}">${escapeHtml(s.action_text)}</a>` : ''}
    </div>`;
    container.appendChild(slide);

    const dot = document.createElement('div');
    dot.className = 'nav-dot' + (i===0 ? ' active' : '');
    dot.setAttribute('onclick', `goToSlide(${i})`);
    nav.appendChild(dot);
  });

  // ensure progress element exists for ProfessionalBillboard
  if(progress) progress.style.width = '0%';

  // refresh the billboard controller
  if(window.billboard && typeof window.billboard.refresh === 'function') {
    window.billboard.refresh();
  } else if(window.billboard && typeof window.billboard.init === 'function') {
    window.billboard.init();
  }
}

function renderProjects(projects) {
  const grid = document.querySelector('.projects-grid');
  if(!grid) return;
  grid.innerHTML = '';
  projects.forEach(p => {
    const card = document.createElement('div');
    card.className = 'project-card';
    card.innerHTML = `<div class="project-thumbnail">${escapeHtml(p.thumbnail || '🎭')}<div class="play-button">▶</div></div>
      <div class="project-details">
        <h3 class="project-title">${escapeHtml(p.title)}</h3>
        <p class="project-meta">${escapeHtml(p.meta || '')}</p>
        <p class="project-description">${escapeHtml(p.description || '')}</p>
      </div>`;
    if(p.youtube_id) card.setAttribute('onclick', `openVideo('${escapeAttr(p.youtube_id)}')`);
    grid.appendChild(card);
  });
}

function renderFeatured(featured) {
  const slider = document.querySelector('.projects-slider');
  if(!slider) return;
  slider.innerHTML = '';
  featured.forEach((p,i) => {
    const slide = document.createElement('div');
    slide.className = 'project-slide' + (i===0 ? ' active' : '');
    slide.innerHTML = `<div class="project-content" style="display:flex;gap:1rem;align-items:center;">
      <div class="project-image" style="min-width:120px;">
        <div class="project-placeholder" style="font-size:2rem;display:flex;align-items:center;justify-content:center;height:100px;border-radius:8px;background:rgba(255,255,255,0.02);">${escapeHtml(p.thumbnail || '🎬')}</div>
        <div class="project-badge" style="margin-top:.5rem;color:var(--gold);font-weight:600;">${escapeHtml(p.status||'')}</div>
      </div>
      <div class="project-info" style="flex:1;">
        <h3 style="margin:0 0 .5rem 0;font-size:1.25rem;">${escapeHtml(p.title)}</h3>
        <p class="project-genre" style="margin:0 .5rem .5rem 0;color:var(--muted)">${escapeHtml(p.meta||'')}</p>
        <p style="margin:0;color:var(--white);opacity:.9">${escapeHtml(p.description||'')}</p>
        <div class="project-stats" style="margin-top:.6rem;display:flex;gap:1rem;color:var(--muted);font-size:.95rem;">
          <span>📅 ${escapeHtml(p.release_date || 'TBD')}</span>
          <span>⭐ ${escapeHtml(p.featured ? 'Featured' : (p.status || ''))}</span>
        </div>
      </div>
    </div>`;
    slider.appendChild(slide);
  });

  // Initialize slider controls (dots + autoplay)
  initFeaturedSlider();
}

/* Featured slider controller */
function initFeaturedSlider() {
  const slider = document.querySelector('.projects-slider');
  if(!slider) return;
  // clear any existing state
  if (window._featured && window._featured.interval) {
    clearInterval(window._featured.interval);
  }

  const slides = Array.from(slider.querySelectorAll('.project-slide'));
  const dotsContainer = document.querySelector('.slider-dots');
  if (dotsContainer) dotsContainer.innerHTML = '';

  slides.forEach((s,i) => {
    s.style.display = i === 0 ? 'block' : 'none';
    s.classList.toggle('active', i === 0);
    if (dotsContainer) {
      const dot = document.createElement('span');
      dot.className = 'dot' + (i===0 ? ' active' : '');
      dot.addEventListener('click', () => currentSlide(i));
      dotsContainer.appendChild(dot);
    }
  });

  window._featured = {
    slider,
    slides,
    dots: dotsContainer ? Array.from(dotsContainer.children) : [],
    current: 0,
    interval: setInterval(() => { if(window._featured) nextSlide(); }, 6000)
  };
}

function showFeatured(index) {
  const f = window._featured;
  if(!f) return;
  index = (index + f.slides.length) % f.slides.length;
  f.slides.forEach((s,i) => {
    s.style.display = i === index ? 'block' : 'none';
    s.classList.toggle('active', i === index);
  });
  if (f.dots) f.dots.forEach((d,i) => d.classList.toggle('active', i === index));
  f.current = index;
}

function nextSlide() {
  const f = window._featured;
  if(!f) return;
  showFeatured((f.current + 1) % f.slides.length);
}
function previousSlide() {
  const f = window._featured;
  if(!f) return;
  showFeatured((f.current - 1 + f.slides.length) % f.slides.length);
}
function currentSlide(n) { showFeatured(n); }

function renderNominees(nominees) {
  const container = document.getElementById('nomineesGrid');
  if(!container) return;
  container.innerHTML = '';
  nominees.forEach(n => {
    const card = document.createElement('div');
    card.className = 'nominee-card';
    card.setAttribute('data-nominee-id', n.id);
    card.innerHTML = `<div class="nominee-avatar">${escapeHtml(n.avatar||'NA')}</div>
      <h4 class="nominee-name">${escapeHtml(n.name)}</h4>
      <p class="nominee-role">${escapeHtml(n.role_description)}</p>
      <p class="vote-count">${escapeHtml(String(n.votes || 0))} votes</p>`;
    card.addEventListener('click', () => {
      document.querySelectorAll('.nominee-card').forEach(c => c.classList.remove('selected'));
      card.classList.add('selected');
      window.selectedNominee = n.id;
      document.getElementById('voteButton').disabled = false;
    });
    container.appendChild(card);
  });
}

function renderStats(stats) {
  const grid = document.querySelector('.stats-grid');
  if(!grid) return;
  grid.innerHTML = '';
  stats.forEach(s => {
    const card = document.createElement('div');
    card.className = 'stat-card';
    card.innerHTML = `<div class="stat-icon">📊</div>
      <div class="stat-number">${escapeHtml(s.value_text)}</div>
      <div class="stat-label">${escapeHtml(s.label)}</div>
      <div class="stat-trend">${escapeHtml(s.trend||'')}</div>`;
    grid.appendChild(card);
  });
}

function renderNews(news) {
  const container = document.querySelector('.news-container');
  if(!container) return;
  container.innerHTML = '';
  news.forEach(n => {
    const item = document.createElement('div');
    item.className = 'news-item';
    item.innerHTML = `<div class="news-date">${new Date(n.created_at).toLocaleDateString()}</div>
      <div class="news-content"><h4>${escapeHtml(n.title)}</h4><p>${escapeHtml(n.content)}</p></div>`;
    container.appendChild(item);
  });
}

function renderTimeline(items) {
  const container = document.querySelector('.timeline');
  if(!container) return;
  container.innerHTML = '';
  items.forEach(t => {
    const el = document.createElement('div');
    el.className = 'timeline-item';
    el.innerHTML = `<div class="timeline-year">${escapeHtml(t.year)}</div>
      <div class="timeline-content"><h4>${escapeHtml(t.title)}</h4><p>${escapeHtml(t.content)}</p></div>`;
    container.appendChild(el);
  });
}

function renderQuickActions(actions) {
  const grid = document.querySelector('.actions-grid');
  if(!grid) return;
  grid.innerHTML = '';
  actions.forEach(a => {
    const card = document.createElement('div');
    card.className = 'action-card';
    card.innerHTML = `<div class="action-icon">${escapeHtml(a.icon||'⚡')}</div><h4>${escapeHtml(a.title)}</h4><p>${escapeHtml(a.description)}</p>`;
    if (a.link) card.addEventListener('click', () => window.location.href = a.link);
    grid.appendChild(card);
  });
}

/* Fetch content and render — instantiate/refresh billboard after DOM injection */
document.addEventListener('DOMContentLoaded', () => {
  fetch('ajax/fetch_home.php')
    .then(r => {
      if (!r.ok) throw new Error('Network response was not ok');
      return r.json();
    })
    .then(data => {
      renderBillboard(data.billboards || []);
      renderProjects(data.projects || []);
      renderFeatured(data.featured || []);
      renderNominees(data.nominees || []);
      renderStats(data.stats || []);
      renderNews(data.news || []);
      renderTimeline(data.timeline || []);
      renderQuickActions(data.quick_actions || []);

      // instantiate or refresh the billboard controller now slides exist
      if (window.billboard && typeof window.billboard.refresh === 'function') {
        window.billboard.refresh();
      } else {
        window.billboard = new ProfessionalBillboard(); // constructor should call refresh internally
      }
    })
    .catch(err => {
      console.error('Fetch home data error:', err);
    });
});

        // Interactive functions
        function showTrailer() {
            alert('🎬 Trailer coming soon! Our first major production "Echoes of Tomorrow" will premiere its trailer next month. Subscribe to our newsletter to be the first to see it!');
        }

        function showArtistProfile(artistId) {
            const profiles = {
                maya: "Maya Rodriguez - Award-winning actor and director with over 10 years of experience in independent film. Known for her powerful performances in 'Silent Voices' and 'The Bridge Between Us'. Maya specializes in character-driven narratives and has won multiple festival awards for her directorial work.",
                james: "James Chen - Visionary cinematographer with expertise in both traditional and digital filmmaking. His work on 'Neon Dreams' and 'Urban Poetry' has been recognized internationally. James brings a unique visual style that combines classical composition with modern techniques.",
                aisha: "Aisha Patel - Creative producer and writer with a passion for diverse storytelling. She has developed over 15 projects across genres and is known for her ability to identify and nurture emerging talent. Aisha's recent work includes the acclaimed series 'Voices of Tomorrow'.",
                carlos: "Carlos Silva - Innovative music composer who creates emotionally resonant scores for film and digital media. His compositions blend traditional orchestral elements with contemporary electronic sounds. Carlos has scored over 30 productions and collaborates closely with directors to enhance narrative impact.",
                zara: "Zara Kim - Production designer with a background in architecture and fine arts. She creates immersive visual worlds that serve the story while maintaining artistic integrity. Zara's attention to detail and creative problem-solving have made her a sought-after collaborator.",
                david: "David Okafor - Master editor and post-production specialist with expertise in narrative pacing and visual storytelling. His editing work has helped shape award-winning films and web series. David is known for his collaborative approach and technical innovation."
            };
            
            alert(`🎭 Artist Profile:\n\n${profiles[artistId] || 'Profile coming soon!'}\n\nFull portfolio and video reel available on request. Contact us for collaboration opportunities!`);
        }

        function showPoll() {
            const currentPoll = `📊 Current Poll: What genre should our next web series explore?

🎭 Drama (35%)
🚀 Sci-Fi (28%) 
😄 Comedy (22%)
🕵️ Mystery (15%)

Vote by emailing your choice to polls@saadanfilms.com or through our social media!`;
            alert(currentPoll);
        }

        function showSubmission() {
            alert('🎨 Creative Competition - July 2025\n\n"Future Stories" - Submit your vision of storytelling in 2030!\n\nCategories:\n• Short Film (under 5 minutes)\n• Digital Art\n• Written Concept (500 words)\n• Photography Series\n\nPrizes: Winner featured on our website + $500 prize\n\nSubmission deadline: July 31, 2025\nEmail: competitions@saadanfilms.com');
        }

        function showFanWall() {
            alert('👥 Fan Wall Highlights\n\n⭐ Sarah M. - "Amazing work on the behind-the-scenes content!"\n⭐ Alex T. - Created fan art for "Echoes of Tomorrow"\n⭐ Jordan K. - "The diversity in storytelling is inspiring"\n⭐ Maria L. - Submitted original short film concept\n\nWant to be featured? Tag us @SaadanFilms with your creative content!');
        }

        function subscribeNewsletter() {
            const email = document.getElementById('newsletterEmail')?.value || 'your email';
            alert(`📧 Thank you for subscribing!\n\nWe'll send you:\n• Exclusive behind-the-scenes content\n• Early access to casting calls\n• Monthly artist spotlights\n• Project updates and announcements\n\nWelcome to the Saadan Films community!`);
            if(document.getElementById('newsletterEmail')) {
                document.getElementById('newsletterEmail').value = '';
            }
        }

        function submitAudition(event) {
            event.preventDefault();
            const name = document.getElementById('actorName').value;
            const project = document.getElementById('castingProject').value;
            
            alert(`🎬 Audition Submitted Successfully!\n\nThank you, ${name}!\n\nYour submission for "${project}" has been received. Our casting team will review your materials and contact you within 2-3 weeks.\n\nNext steps:\n• Keep your phone/email accessible\n• Prepare additional materials if requested\n• Follow us for casting updates\n\nBreak a leg!`);
            
            // Reset form
            event.target.reset();
        }

        function submitContact(event) {
            event.preventDefault();
            const name = document.getElementById('contactName').value;
            const subject = document.getElementById('contactSubject').value;
            
            alert(`📧 Message Sent Successfully!\n\nThank you, ${name}!\n\nWe've received your message about "${subject}" and will respond within 24-48 hours.\n\nFor urgent matters:\n• Casting: casting@saadanfilms.com\n• Press: press@saadanfilms.com\n• General: info@saadanfilms.com`);
            
            // Reset form
            event.target.reset();
        }

        // Smooth scrolling and animations
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.style.background = 'rgba(0, 0, 0, 0.98)';
            } else {
                nav.style.background = 'rgba(0, 0, 0, 0.95)';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.querySelector('.nav-menu');
            const hamburger = document.querySelector('.hamburger');
            
            if (!nav.contains(event.target) && !hamburger.contains(event.target)) {
                nav.classList.remove('active');
            }
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });

        // Add some interactive elements
        document.querySelectorAll('.artist-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelector('.nav-menu').classList.remove('active');
            }
        });

        

  
    function selectNominee(nominee) {
        if (hasVoted) return;
        
        // Remove previous selection
        document.querySelectorAll('.nominee-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked nominee
        document.querySelector(`[data-nominee="${nominee}"]`).classList.add('selected');
        selectedNominee = nominee;
        
        // Enable vote button
        document.getElementById('voteButton').disabled = false;
    }

    function submitVote() {
        if (!selectedNominee || hasVoted) return;
        
        // Simulate vote submission
        const voteData = {
            nominee: selectedNominee,
            timestamp: new Date().toISOString(),
            ip: 'simulated'
        };
        
        // Store vote locally (in real app, send to server)
        localStorage.setItem('saadan_vote_2025', JSON.stringify(voteData));
        localStorage.setItem('saadan_voted_2025', 'true');
        
        // Update UI
        document.getElementById('voteButton').textContent = 'Vote Submitted!';
        document.getElementById('voteButton').disabled = true;
        hasVoted = true;
        
        // Update vote count (simulate)
        const currentCard = document.querySelector(`[data-nominee="${selectedNominee}"]`);
        const voteCountElement = currentCard.querySelector('.vote-count');
        const currentVotes = parseInt(voteCountElement.textContent.match(/\d+/)[0]);
        voteCountElement.textContent = `${currentVotes + 1} votes`;
        
        // Show confirmation
        setTimeout(() => {
            alert('Thank you for voting! Results will be announced on January 1, 2026.');
        }, 500);
    }

    // Auto-update live stats (simulate)
    function updateLiveStats() {
        const stats = {
            fans: Math.floor(15200 + Math.random() * 100),
            projects: 8 + Math.floor(Math.random() * 3),
            artists: 25 + Math.floor(Math.random() * 5)
        };
        
        // Update any live counters you have on the page
        console.log('Live stats updated:', stats);
    }

    // Update stats every 30 seconds
    setInterval(updateLiveStats, 30000);

    // Initialize
    updateLiveStats();


    // Voting functionality
    let selectedNominee = null;
    let hasVoted = localStorage.getItem('saadan_voted_2025') === 'true';

    if (hasVoted) {
        document.getElementById('voteButton').textContent = 'Already Voted';
        document.getElementById('voteButton').disabled = true;
    }

    function selectNominee(nominee) {
        if (hasVoted) return;
        
        // Remove previous selection
        document.querySelectorAll('.nominee-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to clicked nominee
        document.querySelector(`[data-nominee="${nominee}"]`).classList.add('selected');
        selectedNominee = nominee;
        
        // Enable vote button
        document.getElementById('voteButton').disabled = false;
    }

    function submitVote() {
        if (!selectedNominee || hasVoted) return;
        
        // Simulate vote submission
        const voteData = {
            nominee: selectedNominee,
            timestamp: new Date().toISOString(),
            ip: 'simulated'
        };
        
        // Store vote locally (in real app, send to server)
        localStorage.setItem('saadan_vote_2025', JSON.stringify(voteData));
        localStorage.setItem('saadan_voted_2025', 'true');
        
        // Update UI
        document.getElementById('voteButton').textContent = 'Vote Submitted!';
        document.getElementById('voteButton').disabled = true;
        hasVoted = true;
        
        // Update vote count (simulate)
        const currentCard = document.querySelector(`[data-nominee="${selectedNominee}"]`);
        const voteCountElement = currentCard.querySelector('.vote-count');
        const currentVotes = parseInt(voteCountElement.textContent.match(/\d+/)[0]);
        voteCountElement.textContent = `${currentVotes + 1} votes`;
        
        // Show confirmation
        setTimeout(() => {
            alert('Thank you for voting! Results will be announced on January 1, 2026.');
        }, 500);
    }

    // Auto-update live stats (simulate)
    function updateLiveStats() {
        const stats = {
            fans: Math.floor(15200 + Math.random() * 100),
            projects: 8 + Math.floor(Math.random() * 3),
            artists: 25 + Math.floor(Math.random() * 5)
        };
        
        // Update any live counters you have on the page
        console.log('Live stats updated:', stats);
    }

    // Update stats every 30 seconds
    setInterval(updateLiveStats, 30000);

    // Initialize
    updateLiveStats();

     class ProfessionalBillboard {
    constructor() {
        this.slideInterval = 8000;
        this.timer = null;
        this.progressTimer = null;
        this.isTransitioning = false;
        this._eventsBound = false;
        // do not query slides here — allow refresh to attach
        this.currentSlide = 0;
        this.refresh();
    }

    // call after DOM changes (or after renderBillboard)
    refresh() {
        this.slides = document.querySelectorAll('.billboard-slide');
        this.navDots = document.querySelectorAll('.nav-dot');
        this.progressBar = document.querySelector('.progress-bar');
        // ensure we have at least one slide
        if (!this.slides || this.slides.length === 0) return;
        // re-bind events only once
        if (!this._eventsBound) {
            this.setupEventListeners();
            this._eventsBound = true;
        }
        this.preloadVideos();
        this.currentSlide = 0;
        this.showSlide(0);
        this.startAutoPlay();
    }

    setupEventListeners() {
        const billboard = document.querySelector('.live-billboard');
        if (!billboard) return;
        billboard.addEventListener('mouseenter', () => this.pauseAutoPlay());
        billboard.addEventListener('mouseleave', () => this.startAutoPlay());
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.previousSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === ' ') { e.preventDefault(); this.toggleAutoPlay(); }
        });
        // click on navDots (nav-dots are dynamic; bind through refresh)
    }

    showSlide(index) {
        this.slides.forEach((s,i) => s.classList.toggle('active', i === index));
        this.navDots.forEach((d,i) => d.classList.toggle('active', i === index));
        // handle video play/pause
        this.slides.forEach((s,i) => {
            const v = s.querySelector('video');
            if (v) {
                if (i === index) { v.currentTime = 0; v.play().catch(()=>{}); } else { v.pause(); }
            }
        });
    }

    goToSlide(index) { this.transitionSlides(this.currentSlide, index); }

    transitionSlides(from, to) {
        const fromSlide = this.slides[from];
        const toSlide = this.slides[to];
        const fromVideo = fromSlide.querySelector('video');
        const toVideo = toSlide.querySelector('video');
        
        // Prepare the incoming slide
        toSlide.style.opacity = '0';
        toSlide.style.transform = 'scale(1.05)';
        
        // Start fade out of current slide
        fromSlide.classList.add('fade-out');
        
        setTimeout(() => {
            // Remove active class from previous slide
            fromSlide.classList.remove('active', 'fade-out');
            
            // Add active class to new slide
            toSlide.classList.add('active');
            toSlide.style.opacity = '1';
            toSlide.style.transform = 'scale(1)';
            
            // Handle video playback
            if (fromVideo && typeof fromVideo.pause === 'function') {
                fromVideo.pause();
            }
            if (toVideo && typeof toVideo.play === 'function') {
                toVideo.currentTime = 0;
                toVideo.play().catch(e => console.log('Video play prevented:', e));
            }
        }, 500);
    }
    
            nextSlide() {
                const nextIndex = (this.currentSlide + 1) % this.slides.length;
                this.goToSlide(nextIndex);
            }
            
            previousSlide() {
                const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
                this.goToSlide(prevIndex);
            }
            
            startAutoPlay() {
                this.pauseAutoPlay();
                this.timer = setInterval(() => this.nextSlide(), this.slideInterval);
                this.startProgress();
            }
            
            pauseAutoPlay() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
                this.pauseProgress();
            }
            
            toggleAutoPlay() {
                if (this.timer) {
                    this.pauseAutoPlay();
                } else {
                    this.startAutoPlay();
                }
            }
            
            preloadVideos() {
                const videos = document.querySelectorAll('.billboard-video');
                videos.forEach((video, index) => {
                    if (index !== this.currentSlide) {
                        video.load();
                    }
                });
            }
            
            startProgress() {
                this.resetProgress();
                let progress = 0;
                const increment = 100 / (this.slideInterval / 50);
                
                this.progressTimer = setInterval(() => {
                    progress += increment;
                    this.progressBar.style.width = Math.min(progress, 100) + '%';
                    
                    if (progress >= 100) {
                        clearInterval(this.progressTimer);
                    }
                }, 50);
            }
            
            pauseProgress() {
                if (this.progressTimer) {
                    clearInterval(this.progressTimer);
                    this.progressTimer = null;
                }
            }
            
            resetProgress() {
                this.progressBar.style.width = '0%';
                if (this.progressTimer) {
                    clearInterval(this.progressTimer);
                }
            }
        }
        
        // Interactive functions
        function showTrailer() {
            alert('🎬 Opening trailer in cinematic player...\n\nExperience our latest work in full HD with surround sound. Get ready for an unforgettable journey into storytelling excellence.');
        }
        
        function showAudition() {
            alert('🎭 Casting Portal\n\nReady to be part of our next production?\n\n• Upload your reel\n• Schedule virtual auditions\n• Join our talent database\n\nTalent scouts are standing by!');
        }
        
        function showAwards() {
            alert('🏆 Award-Winning Excellence\n\nOur commitment to cinematic artistry has been recognized globally:\n\n• Best Cinematography - Global Indie Festival\n• Outstanding Direction - International Film Awards\n• Audience Choice - Regional Cinema Celebration\n\nView our complete portfolio and accolades.');
        }
        
        function joinCommunity() {
            alert('👥 Welcome to the Saadan Films Family!\n\n🌟 Exclusive content access\n🎬 Behind-the-scenes footage\n🎭 Cast and crew interactions\n📧 Early announcements\n🎪 VIP event invitations\n\nJoin 15.2K+ passionate film enthusiasts worldwide!');
        }
        
        function goToSlide(index) {
            if (window.billboard) {
                window.billboard.goToSlide(index);
            }
        }
        
        // Initialize the billboard when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            window.billboard = new ProfessionalBillboard();
        });
        
        // Handle page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (window.billboard) {
                if (document.hidden) {
                    window.billboard.pauseAutoPlay();
                } else {
                    window.billboard.startAutoPlay();
                }
            }
        });
document.addEventListener('DOMContentLoaded', () => {
  fetch('ajax/fetch_home.php')
    .then(r => r.json())
    .then(data => {
      renderBillboard(data.billboards || []);
      renderProjects(data.projects || []);
      renderFeatured(data.featured || []);
      renderNominees(data.nominees || []);
      renderStats(data.stats || []);
      renderNews(data.news || []);
      renderTimeline(data.timeline || []);
          renderQuickActions(data.quick_actions || []);
        });
    });
</script>
</body>
</html>

