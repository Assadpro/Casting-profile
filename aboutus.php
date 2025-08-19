<?php
// Dynamic About page: loads content from DB
include 'header.php';
include __DIR__ . '/config/database.php'; // must set $conn (mysqli)

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Fetch about main info
$about = $conn->query("SELECT * FROM about_info ORDER BY id DESC LIMIT 1");
$about = $about ? $about->fetch_assoc() : null;

// Fetch team
$team = [];
if ($res = $conn->query("SELECT * FROM team ORDER BY ord ASC, id ASC")) {
  while ($row = $res->fetch_assoc()) $team[] = $row;
}

// Fetch stats (reuse stats table if exists)
$stats = [];
if ($res = $conn->query("SELECT * FROM stats")) {
  while ($row = $res->fetch_assoc()) $stats[] = $row;
}

// Fetch timeline (if exists)
$timeline = [];
if ($res = $conn->query("SELECT * FROM timeline ORDER BY position ASC")) {
  while ($row = $res->fetch_assoc()) $timeline[] = $row;
}

// Fetch awards & partners
$awards = [];
if ($res = $conn->query("SELECT * FROM awards ORDER BY year DESC")) {
  while ($row = $res->fetch_assoc()) $awards[] = $row;
}

$partners = [];
if ($res = $conn->query("SELECT * FROM partners")) {
  while ($row = $res->fetch_assoc()) $partners[] = $row;
}

// Fallbacks
$title = $about['title'] ?? 'About Saadan Films';
$subtitle = $about['subtitle'] ?? 'Studio for Arts, Ambition & Diversity Across the Nation — producing bold storytelling, developing talent, and connecting audiences globally.';
$mission = $about['mission'] ?? 'To create a sustainable platform for diverse filmmakers — nurturing talent, fostering collaborations, and delivering emotionally powerful stories that resonate worldwide.';
$vision = $about['vision'] ?? 'To be a recognized home for cultural storytelling where ambitious creators build careers and audiences discover transformative work.';
$hero_tagline = $about['hero_tagline'] ?? 'Studio for Arts, Ambition, and Diversity Across Nation';
$cta_text = $about['cta_text'] ?? 'Browse Films';
$cta_link = $about['cta_link'] ?? 'catalog.php';

?>
<link rel="stylesheet" href="assets/css/styles.css" type="text/css">

<!-- Hero Section -->
    <section id="hero" class="hero section active" style="min-height:320px; display:flex; align-items:center; padding:2rem 0;">
  <div class="container" style="display:flex;gap:2rem;align-items:center;">
    <div style="flex:1;display:flex;align-items:center;justify-content:center;">
      <img src="logo/saadanfilm-logo.png" alt="Saadan Films" style="max-width:360px;width:60%;height:auto;">
    </div>
    <div style="flex:1;">
      <h1 class="section-title"><?= e($hero_tagline) ?></h1>
      <p style="color:var(--muted);font-size:1.1rem;max-width:60ch"><?= e($subtitle) ?></p>
      <div style="margin-top:1rem;">
        <a class="btn btn-primary" href="<?= e($cta_link) ?>"><?= e($cta_text) ?></a>
        <a class="btn btn-secondary" href="contact.php" style="margin-left:.6rem">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<section id="about" class="section active about-section">
  <div class="container">
    <div class="about-grid">
      <div class="about-card wide">
        <h2><?= e($title) ?></h2>
        <p><?= nl2br(e($subtitle)) ?></p>
      </div>

      <div class="about-card">
        <h3>Our Mission</h3>
        <p><?= nl2br(e($mission)) ?></p>
      </div>

      <div class="about-card">
        <h3>Our Vision</h3>
        <p><?= nl2br(e($vision)) ?></p>
      </div>

      <div class="about-card">
        <h3>What We Do</h3>
        <ul class="feature-list">
          <li>Original film & series production</li>
          <li>Talent development & workshops</li>
          <li>Festivals & distribution support</li>
          <li>Community outreach & co-productions</li>
        </ul>
      </div>
    </div>

    <div class="impact-section">
      <h2 class="section-title">Our Impact</h2>
      <div class="stats-grid">
        <?php if (!empty($stats)): ?>
          <?php foreach($stats as $s): ?>
            <div class="stat-card">
              <div class="stat-number"><?= e($s['value_text'] ?? '') ?></div>
              <div class="stat-label"><?= e($s['label'] ?? $s['key_name'] ?? '') ?></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="stat-card"><div class="stat-number">120+</div><div class="stat-label">Artists Collaborated</div></div>
          <div class="stat-card"><div class="stat-number">45</div><div class="stat-label">Projects Produced</div></div>
          <div class="stat-card"><div class="stat-number">25K+</div><div class="stat-label">Global Viewers</div></div>
          <div class="stat-card"><div class="stat-number">18</div><div class="stat-label">Festival Awards</div></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="values-section">
      <h2 class="section-title">Core Values</h2>
      <div class="values-grid">
        <div class="value-card"><div class="value-icon">🤝</div><h4>Collaboration</h4><p>We co-create with communities and partners.</p></div>
        <div class="value-card"><div class="value-icon">🎯</div><h4>Ambition</h4><p>We pursue projects that push creative boundaries.</p></div>
        <div class="value-card"><div class="value-icon">🌍</div><h4>Inclusion</h4><p>We amplify diverse perspectives across our slate.</p></div>
        <div class="value-card"><div class="value-icon">🔁</div><h4>Sustainability</h4><p>We build long-term careers for artists and crews.</p></div>
      </div>
    </div>

    <div class="team-section">
      <h2 class="section-title">Leadership & Creative Team</h2>
      <div class="team-grid">
        <?php if (!empty($team)): ?>
          <?php foreach($team as $m): ?>
            <div class="team-card" data-name="<?= e($m['name']) ?>" data-role="<?= e($m['role']) ?>" data-bio="<?= e($m['bio']) ?>" data-avatar="<?= e($m['avatar']) ?>" data-email="<?= e($m['email'] ?? '') ?>" data-links='<?= json_encode(['website'=>$m['website'] ?? '','twitter'=>$m['twitter'] ?? '','linkedin'=>$m['linkedin'] ?? '']) ?>'>
                <div class="team-avatar"><?= e($m['avatar'] ?: strtoupper(substr($m['name'],0,2))) ?></div>
                <div class="team-body">
                  <h4><?= e($m['name']) ?></h4>
                  <div class="role"><?= e($m['role']) ?></div>
                  <div class="bio"><?= e($m['bio']) ?></div>
                  <div class="team-actions">
                    <button class="btn-ghost view-profile">View Profile</button>
                    <a class="btn-ghost" href="mailto:<?= e($m['email'] ?? '') ?>">Email</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- fallback -->
            <div class="team-card"><div class="team-avatar">SR</div><div class="team-body"><h4>Mariam Saadan</h4><div class="role">Founder & Creative Director</div><div class="bio">Seasoned producer and director.</div><div class="team-actions"><button class="btn-ghost view-profile">View Profile</button></div></div></div>
            <div class="team-card"><div class="team-avatar">AJ</div><div class="team-body"><h4>Alex Johnson</h4><div class="role">Head of Production</div><div class="bio">Leads production operations.</div><div class="team-actions"><button class="btn-ghost view-profile">View Profile</button></div></div></div>
          <?php endif; ?>
        </div>
      </div>

<!-- Profile Modal -->
<div id="profileModal" class="modal-backdrop" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="profileTitle">
    <header>
      <h3 id="profileTitle">Profile</h3>
      <button class="close" data-close>✕</button>
    </header>
    <div class="profile-grid">
      <div class="profile-avatar" id="profileAvatar">NA</div>
      <div>
        <h4 id="profileName"></h4>
        <div id="profileRole" class="role"></div>
        <p id="profileBio" style="margin-top:.5rem;color:var(--muted)"></p>
        <p id="profileContact" class="form-note"></p>
        <div id="profileLinks" style="margin-top:.6rem"></div>
      </div>
    </div>
    <div style="margin-top:.8rem;display:flex;justify-content:flex-end">
      <button class="btn btn-primary" id="connectBtn">Connect</button>
    </div>
  </div>
</div>

<!-- Careers Modal (application form) -->
<div id="careersModal" class="modal-backdrop" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="careersTitle">
    <header>
      <h3 id="careersTitle">Apply — Saadan Films</h3>
      <button class="close" data-close>✕</button>
    </header>
    <form id="applicationForm" enctype="multipart/form-data">
      <div class="form-row">
        <label for="appName">Full name</label>
        <input id="appName" name="name" class="input" required>
      </div>
      <div class="form-row">
        <label for="appEmail">Email</label>
        <input id="appEmail" name="email" type="email" class="input" required>
      </div>
      <div class="form-row">
        <label for="appRole">Role applying for</label>
        <input id="appRole" name="role" class="input" required>
      </div>
      <div class="form-row">
        <label for="appCover">Cover note</label>
        <textarea id="appCover" name="cover" class="input"></textarea>
      </div>
      <div class="form-row">
        <label for="appResume">Upload resume / portfolio (PDF, max 4MB)</label>
        <input id="appResume" name="resume" type="file" accept=".pdf,.doc,.docx" class="input" required>
      </div>
      <div class="form-actions">
        <div class="form-note" id="appStatus"></div>
        <div>
          <button type="button" class="btn btn-secondary" data-close>Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Application</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Partnerships contact modal -->
<div id="contactModal" class="modal-backdrop" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="contactTitle">
    <header>
      <h3 id="contactTitle">Partnerships / Collaborations</h3>
      <button class="close" data-close>✕</button>
    </header>
    <form id="contactForm">
      <div class="form-row"><label for="cName">Your name</label><input id="cName" name="name" class="input" required></div>
      <div class="form-row"><label for="cEmail">Email</label><input id="cEmail" name="email" type="email" class="input" required></div>
      <div class="form-row"><label for="cOrg">Organization</label><input id="cOrg" name="organization" class="input"></div>
      <div class="form-row"><label for="cMessage">Message</label><textarea id="cMessage" name="message" class="input" required></textarea></div>
      <div class="form-actions">
        <div class="form-note" id="contactStatus"></div>
        <div>
          <button type="button" class="btn btn-secondary" data-close>Cancel</button>
          <button type="submit" class="btn btn-primary">Send Message</button>
        </div>
      </div>
    </form>
  </div>
</div>

 <div class="timeline-section">
      <h2 class="section-title">Our Journey</h2>
      <div class="timeline">
        <?php if (!empty($timeline)): ?>
          <?php foreach($timeline as $t): ?>
            <div class="timeline-item <?= (!empty($t['position']) && $t['position']==(count($timeline)-1)) ? 'active' : '' ?>">
              <div class="timeline-year"><?= e($t['year']) ?></div>
              <div class="timeline-body"><h4><?= e($t['title']) ?></h4><p><?= e($t['content']) ?></p></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="timeline-item active"><div class="timeline-year">2025</div><div class="timeline-body"><h4>Major Productions</h4><p>Expanding into feature production and international co-productions.</p></div></div>
        <?php endif; ?>
      </div>
    </div>

      <div class="partners">
        <h3>Partners</h3>
        <div class="partner-logos">
          <?php if (!empty($partners)): ?>
            <?php foreach($partners as $p): ?>
              <div class="partner"><?= $p['logo'] ? '<img src="'.e($p['logo']).'" alt="'.e($p['name']).'" style="max-height:36px">' : e($p['name']) ?></div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="partner">🎭</div><div class="partner">📺</div><div class="partner">🌐</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="about-cta">
      <div class="cta-left">
        <h3>Join our team or collaborate</h3>
        <p>We're always looking for creative producers, editors and storytellers. Submit your portfolio or reach out for partnerships.</p>
      </div>
      <div class="cta-right">
        <a class="btn btn-primary" href="careers.php">See Open Roles</a>
        <a class="btn btn-secondary" href="contact.php">Contact Partnerships</a>
      </div>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>



<script>
(function(){
  // modal helpers
  function openModal(id){ document.getElementById(id).classList.add('active'); document.getElementById(id).setAttribute('aria-hidden','false'); }
  function closeModal(el){
    const m = el.closest('.modal-backdrop') || el;
    m.classList.remove('active'); m.setAttribute('aria-hidden','true');
  }
  document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click', e=> closeModal(e.target)));
  document.querySelectorAll('.modal-backdrop').forEach(bg=>{
    bg.addEventListener('click', e=>{ if(e.target===bg) closeModal(bg); });
  });

  // open careers & contact via CTA
  document.querySelectorAll('.about-cta .btn.btn-primary, .about-cta .btn.btn-secondary').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
      e.preventDefault();
      if(btn.textContent.trim().toLowerCase().includes('roles')) openModal('careersModal');
      else openModal('contactModal');
    });
  });

  // view profile
  document.querySelectorAll('.view-profile').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
      const card = btn.closest('.team-card');
      const name = card.dataset.name || '';
      const role = card.dataset.role || '';
      const bio = card.dataset.bio || '';
      const avatar = card.dataset.avatar || (name ? name.slice(0,2).toUpperCase() : 'NA');
      const email = card.dataset.email || '';
      const links = JSON.parse(card.dataset.links || '{}');

      document.getElementById('profileName').textContent = name;
      document.getElementById('profileRole').textContent = role;
      document.getElementById('profileBio').textContent = bio;
      document.getElementById('profileAvatar').textContent = avatar;
      document.getElementById('profileContact').textContent = email ? 'Email: '+email : '';
      const linksEl = document.getElementById('profileLinks');
      linksEl.innerHTML = '';
      if (links.website) linksEl.innerHTML += `<a class="btn-ghost" href="${links.website}" target="_blank">Website</a> `;
      if (links.twitter) linksEl.innerHTML += `<a class="btn-ghost" href="${links.twitter}" target="_blank">Twitter</a> `;
      if (links.linkedin) linksEl.innerHTML += `<a class="btn-ghost" href="${links.linkedin}" target="_blank">LinkedIn</a> `;
      openModal('profileModal');
    });
  });

  // application form submit (multipart)
  const appForm = document.getElementById('applicationForm');
  if(appForm){
    appForm.addEventListener('submit', async (ev)=>{
      ev.preventDefault();
      const status = document.getElementById('appStatus'); status.textContent = 'Uploading...';
      const fd = new FormData(appForm);
      try {
        const res = await fetch('submit_application.php', { method:'POST', body: fd });
        const j = await res.json();
        if(j.success){ status.textContent = 'Application submitted. Thank you.'; appForm.reset(); setTimeout(()=>closeModal(appForm),1500); }
        else status.textContent = j.error || 'Submission failed.';
      } catch(err){ status.textContent = 'Network error.'; }
    });
  }

  // contact form
  const contactForm = document.getElementById('contactForm');
  if(contactForm){
    contactForm.addEventListener('submit', async (ev)=>{
      ev.preventDefault();
      const status = document.getElementById('contactStatus'); status.textContent = 'Sending...';
      const fd = new FormData(contactForm);
      try {
        const res = await fetch('submit_contact.php', { method:'POST', body: fd, headers: { 'Accept':'application/json' } });
        const j = await res.json();
        if(j.success){ status.textContent = 'Message sent. We will respond soon.'; contactForm.reset(); setTimeout(()=>closeModal(contactForm),1500); }
        else status.textContent = j.error || 'Send failed.';
      } catch(err){ status.textContent = 'Network error.'; }
    });
  }

  // connect button in profile opens contact modal prefilled
  const connectBtn = document.getElementById('connectBtn');
  if(connectBtn) connectBtn.addEventListener('click', ()=>{
    const name = document.getElementById('profileName').textContent;
    const role = document.getElementById('profileRole').textContent;
    closeModal(connectBtn);
    openModal('contactModal');
    document.getElementById('cMessage').value = `Hi, I'd like to discuss collaborating with ${name} (${role}).`;
  });

})();
</script>


