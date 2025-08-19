<?php
include 'header.php';
include 'config/database.php'; // must set $conn (mysqli)

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function table_exists($conn, $name){
  $r = $conn->query("SHOW TABLES LIKE '".$conn->real_escape_string($name)."'");
  return $r && $r->num_rows > 0;
}

/* Auto-create casting_calls + auditions + performers if missing */
if (!table_exists($conn,'casting_calls')) {
  $conn->query("CREATE TABLE IF NOT EXISTS casting_calls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(255) NOT NULL,
    role_title VARCHAR(255) NOT NULL,
    project_type VARCHAR(64) DEFAULT 'Film',
    role_description TEXT,
    description TEXT,
    deadline DATE,
    location VARCHAR(128) DEFAULT '',
    status ENUM('active','closed') DEFAULT 'active',
    slots INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB CHARSET=utf8mb4");
  // sample seed
  $stmt = $conn->prepare("INSERT INTO casting_calls (project_name, role_title, project_type, role_description, description, deadline, location, slots) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $d = date('Y-m-d', strtotime('+21 days'));
  $slots = 3;
  $stmt->bind_param('sssssssi', $p, $r, $t, $rd, $desc, $d, $loc, $slots);
  $p='Silent Voices'; $r='Supporting Lead'; $t='Short Film'; $rd='Female, 25-35, fluent English'; $desc='A character-driven short exploring family ties.'; $loc='City Studio';
  $stmt->execute();
  $p='Afterlight'; $r='Background Actor'; $t='Feature'; $rd='All ethnicities, 18+'; $desc='Day-player roles in a feature production.'; $loc='On location';
  $stmt->execute();
}

if (!table_exists($conn,'auditions')) {
  $conn->query("CREATE TABLE IF NOT EXISTS auditions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    casting_id INT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(64),
    bio TEXT,
    demo_reel VARCHAR(1024),
    status ENUM('submitted','shortlisted','rejected') DEFAULT 'submitted',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX(casting_id)
  ) ENGINE=InnoDB CHARSET=utf8mb4");
}

if (!table_exists($conn,'performers')) {
  $conn->query("CREATE TABLE IF NOT EXISTS performers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    gender ENUM('male','female','other') DEFAULT 'other',
    age_range VARCHAR(50) DEFAULT '',
    location VARCHAR(128) DEFAULT '',
    bio TEXT,
    headshot VARCHAR(1024) DEFAULT '',
    contact_email VARCHAR(255) DEFAULT '',
    social JSON DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB CHARSET=utf8mb4");
  // seed sample performers
  $stmt = $conn->prepare("INSERT INTO performers (name, gender, age_range, location, bio, headshot, contact_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param('sssssss', $n, $g, $a, $loc, $bio, $headshot, $email);
  $n='Mariam Saadan'; $g='female'; $a='30-40'; $loc='Nairobi'; $bio='Award-winning director & actor.'; $headshot=''; $email='mariam@example.com'; $stmt->execute();
  $n='Alex Johnson'; $g='male'; $a='28-35'; $loc='Cape Town'; $bio='Experienced film actor and stunt performer.'; $headshot=''; $email='alex@example.com'; $stmt->execute();
  $n='Lina Patel'; $g='female'; $a='26-34'; $loc='Mumbai'; $bio='Stage and screen actress with classical training.'; $headshot=''; $email='lina@example.com'; $stmt->execute();
}

/* Fetch current casting calls into array (safe) */
$casting_calls = [];
$res = $conn->query("SELECT * FROM casting_calls WHERE (deadline IS NULL OR deadline >= CURDATE()) AND status='active' ORDER BY deadline ASC, created_at DESC");
if ($res) while ($row = $res->fetch_assoc()) $casting_calls[] = $row;

/* Fetch the latest shortlisted performers */
$roster = [];
$res2 = $conn->query("SELECT a.*, c.project_name, c.role_title FROM auditions a LEFT JOIN casting_calls c ON a.casting_id = c.id WHERE a.status IN ('shortlisted') ORDER BY a.created_at DESC LIMIT 8");
if ($res2) while ($r = $res2->fetch_assoc()) $roster[] = $r;

/* Fetch registered performers to display */
$performers = [];
$res3 = $conn->query("SELECT * FROM performers ORDER BY created_at DESC LIMIT 12");
if ($res3) while ($p = $res3->fetch_assoc()) $performers[] = $p;
?>

<link rel="stylesheet" href="assets/css/styles.css" type="text/css">
<section id="casting" class="section active">
    <div class="container">
        <h2 class="section-title">Casting & Auditions</h2>

        <!-- replaced inline grid with class so CSS can control transparency/layout -->
        <div class="casting-layout">
          <div>
            <div class="about-card" style="margin-bottom:1rem;">
                <h3>Current Casting Calls</h3>
                <div class="projects-grid" style="margin-top:.75rem;">
                  <?php if (empty($casting_calls)): ?>
                    <p>No active casting calls at the moment. Please check back soon.</p>
                  <?php else: foreach($casting_calls as $c): ?>
                    <div class="project-card" style="padding:1rem; border-radius:10px;">
                      <div style="display:flex; justify-content:space-between; gap:1rem; align-items:flex-start;">
                        <div style="flex:1">
                          <h4 style="margin:0 0 .25rem 0; color:var(--white)"><?php echo e($c['role_title']); ?> <small style="color:var(--muted)">— <?php echo e($c['project_name']); ?></small></h4>
                          <div style="color:var(--muted); font-size:.95rem; margin:.35rem 0;"><?php echo e(mb_strimwidth($c['role_description'] ?: $c['description'], 0, 160, '...')); ?></div>
                          <div style="font-size:.9rem;color:var(--muted)"><strong>Type:</strong> <?php echo e($c['project_type']); ?> • <strong>Location:</strong> <?php echo e($c['location']); ?></div>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:.5rem; align-items:flex-end;">
                          <a class="btn btn-ghost" href="casting_profile.php?id=<?php echo (int)$c['id']; ?>">See All Info</a>
                          <button class="btn btn-primary" onclick="document.getElementById('castingProject').value = '<?php echo (int)$c['id']; ?>'; document.getElementById('actorName').focus(); window.scrollTo({top: document.getElementById('auditionForm').offsetTop - 80, behavior:'smooth'});">Apply</button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="about-card">
                <h3>Audition Submission</h3>
                <form onsubmit="submitAudition(event)" id="auditionForm">
                    <div class="form-group">
                        <label for="actorName">Full Name</label>
                        <input type="text" id="actorName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="actorEmail">Email Address</label>
                        <input type="email" id="actorEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="actorPhone">Phone Number</label>
                        <input type="tel" id="actorPhone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="castingProject">Project Interest</label>
                        <select id="castingProject" class="form-control" required>
                            <option value="">Select a project...</option>
                            <?php foreach($casting_calls as $c): ?>
                              <option value="<?php echo (int)$c['id']; ?>"><?php echo e($c['project_name'].' — '.$c['role_title']); ?></option>
                            <?php endforeach; ?>
                            <option value="general">General Submission</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="actorBio">Brief Bio & Experience</label>
                        <textarea id="actorBio" class="form-control" placeholder="Tell us about your acting experience, training, and what makes you unique..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="demoReel">Demo Reel / Portfolio URL</label>
                        <input type="url" id="demoReel" class="form-control" placeholder="https://...">
                    </div>
                    <div style="display:flex; gap:.6rem; align-items:center;">
                      <button type="submit" class="btn btn-primary" style="flex:1;">Submit Audition</button>
                      <div id="auditionStatus" style="color:var(--muted); font-size:.95rem;"></div>
                    </div>
                </form>
            </div>
          </div>

          <!-- Right column: Casting Roster / Shortlisted + Performers -->
          <aside>
            <div class="about-card casting-roster">
              <h3>Casting Roster</h3>
              <p style="color:var(--muted); margin-top:0;">Featured shortlisted performers</p>
              <?php if (empty($roster)): ?>
                <p style="color:var(--muted)">No shortlisted performers to show yet.</p>
              <?php else: foreach($roster as $r): ?>
                <div class="roster-item">
                  <div class="roster-avatar"><?php echo e(strtoupper(substr($r['name'],0,2))); ?></div>
                  <div style="flex:1;">
                    <div style="color:var(--white);font-weight:700;"><?php echo e($r['name']); ?></div>
                    <div style="color:var(--muted);font-size:.9rem;"><?php echo e($r['project_name'] ? ($r['project_name'].' • '.$r['role_title']) : 'General'); ?></div>
                  </div>
                  <a class="btn btn-ghost" href="mailto:<?php echo e($r['email']); ?>">Contact</a>
                </div>
              <?php endforeach; endif; ?>

              <div class="performers-panel">
                <h4 style="margin: .6rem 0 0 0; color:var(--white)">Registered Performers</h4>
                <div class="performers-grid">
                  <?php if (empty($performers)): ?>
                    <div style="color:var(--muted)">No performers registered yet.</div>
                  <?php else: foreach($performers as $p): ?>
                    <div class="performer-card">
                      <div class="performer-headshot">
                        <?php if (!empty($p['headshot'])): ?>
                          <img src="<?php echo e($p['headshot']); ?>" alt="<?php echo e($p['name']); ?>">
                        <?php else: ?>
                          <?php echo e(strtoupper(substr($p['name'],0,2))); ?>
                        <?php endif; ?>
                      </div>
                      <div class="performer-body">
                        <div class="performer-name"><?php echo e($p['name']); ?></div>
                        <div class="performer-meta"><?php echo e($p['age_range'] ?: '—'); ?> • <?php echo e(ucfirst($p['gender'] ?? '')); ?> • <?php echo e($p['location'] ?: '—'); ?></div>
                        <div class="performer-actions">
                          <a class="btn btn-small btn-ghost" href="performer_profile.php?id=<?php echo (int)$p['id']; ?>">View Profile</a>
                          <a class="btn btn-small btn-primary" href="mailto:<?php echo e($p['contact_email'] ?: ''); ?>">Contact</a>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; endif; ?>
                </div>
              </div>
            </div>
          </aside>
        </div>
    </div>
</section>

<script>
// prefill project selection when redirected from casting_profile (casting_profile sets localStorage.prefill_casting_id)
document.addEventListener('DOMContentLoaded', function(){
  try {
    const pre = localStorage.getItem('prefill_casting_id');
    if (pre && document.getElementById('castingProject')) {
      const sel = document.getElementById('castingProject');
      if ([...sel.options].some(o=>o.value === pre)) sel.value = pre;
      localStorage.removeItem('prefill_casting_id');
      // scroll to form if user came from profile
      if (window.location.hash === '#auditionForm' || pre) {
        setTimeout(()=>{ document.getElementById('auditionForm').scrollIntoView({behavior:'smooth', block:'center'}); }, 150);
      }
    }
  } catch(e){}
});

function submitAudition(event) {
    event.preventDefault();
    const statusEl = document.getElementById('auditionStatus');
    statusEl.textContent = 'Sending...';
    const formData = new FormData();
    formData.append('action', 'submit_audition');
    formData.append('name', document.getElementById('actorName').value.trim());
    formData.append('email', document.getElementById('actorEmail').value.trim());
    formData.append('phone', document.getElementById('actorPhone').value.trim());
    formData.append('project_id', document.getElementById('castingProject').value);
    formData.append('bio', document.getElementById('actorBio').value.trim());
    formData.append('demo_reel', document.getElementById('demoReel').value.trim());

    fetch('ajax/submit_audition.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            statusEl.textContent = 'Submitted — thank you!';
            document.getElementById('auditionForm').reset();
            setTimeout(()=> statusEl.textContent = '', 3000);
        } else {
            statusEl.textContent = data.error || 'Submission failed.';
        }
    })
    .catch(err => {
        console.error(err);
        statusEl.textContent = 'Network error.';
    });
}
</script>

<?php include 'footer.php'; ?>