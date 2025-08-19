<?php
include 'header.php';
include 'config/database.php'; // must set $conn (mysqli)

/* helper */
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function table_exists($conn, $name){
    $r = $conn->query("SHOW TABLES LIKE '".$conn->real_escape_string($name)."'");
    return $r && $r->num_rows > 0;
}

/* Auto-create minimal tables when missing to avoid fatal errors */
if (!table_exists($conn,'polls')) {
    $conn->query("CREATE TABLE IF NOT EXISTS `polls` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `question` VARCHAR(255) NOT NULL,
      `options` TEXT NOT NULL,
      `total_votes` INT NOT NULL DEFAULT 0,
      `status` ENUM('active','closed') NOT NULL DEFAULT 'active',
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB CHARSET=utf8mb4");
    // insert sample poll
    $conn->query("INSERT INTO polls (question, options) VALUES ('Which upcoming genre should we prioritize?', '".json_encode(['Drama','Sci-Fi','Documentary','Comedy'])."')");
}

if (!table_exists($conn,'poll_votes')) {
    $conn->query("CREATE TABLE IF NOT EXISTS `poll_votes` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `poll_id` INT NOT NULL,
      `option_index` INT NOT NULL,
      `voter_ip` VARCHAR(45),
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
      INDEX(poll_id)
    ) ENGINE=InnoDB CHARSET=utf8mb4");
}

if (!table_exists($conn,'fan_submissions')) {
    $conn->query("CREATE TABLE IF NOT EXISTS `fan_submissions` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(255) NOT NULL,
      `content` TEXT NOT NULL,
      `type` ENUM('text','image','video') NOT NULL DEFAULT 'text',
      `author_name` VARCHAR(128),
      `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
      `likes` INT NOT NULL DEFAULT 0,
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB CHARSET=utf8mb4");
}

if (!table_exists($conn,'newsletter_subscribers')) {
    $conn->query("CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB CHARSET=utf8mb4");
}

/* Safe fetch (if queries fail, produce empty result arrays rather than fatal) */
$polls = [];
$submissions = [];
$polls_q = $conn->query("SELECT * FROM polls WHERE status = 'active' ORDER BY created_at DESC LIMIT 3");
if ($polls_q) {
    while ($row = $polls_q->fetch_assoc()) $polls[] = $row;
}
$subs_q = $conn->query("SELECT * FROM fan_submissions WHERE status = 'approved' ORDER BY created_at DESC LIMIT 6");
if ($subs_q) {
    while ($row = $subs_q->fetch_assoc()) $submissions[] = $row;
}
?>

<link rel="stylesheet" href="assets/css/styles.css" type="text/css">
<section id="engagement" class="section active">
    <div class="container">
        <h2 class="section-title">Fan Engagement Hub</h2>
        <div class="engagement-grid">
            <div class="engagement-card">
                <div class="engagement-icon">🗳️</div>
                <h3>Monthly Polls</h3>
                <p>Vote on upcoming projects, casting choices, and creative directions. Your voice matters!</p>
                <button class="btn btn-primary" onclick="showPoll()">Vote Now</button>
            </div>
            <div class="engagement-card">
                <div class="engagement-icon">🎨</div>
                <h3>Creative Competitions</h3>
                <p>Submit your artwork, short films, or creative concepts for monthly competitions.</p>
                <button class="btn btn-primary" onclick="showSubmission()">Submit Entry</button>
            </div>
            <div class="engagement-card">
                <div class="engagement-icon">👥</div>
                <h3>Fan Wall</h3>
                <p>See featured fan content and celebrate our amazing community of supporters.</p>
                <button class="btn btn-primary" onclick="showFanWall()">View Wall</button>
            </div>
            <div class="engagement-card">
                <div class="engagement-icon">📧</div>
                <h3>Newsletter</h3>
                <p>Stay updated with exclusive content and early announcements.</p>
                <div class="form-group" style="margin-top: 1rem;">
                    <input type="email" class="form-control" placeholder="Enter your email" id="newsletterEmail">
                    <button class="btn btn-primary" style="margin-top: 0.5rem; width: 100%;" onclick="subscribeNewsletter()">Subscribe</button>
                </div>
            </div>
        </div>

        <!-- Active Polls Section -->
        <div class="polls-section" style="margin-top: 3rem;">
            <h3>Active Polls</h3>
            <div class="polls-grid">
                <?php if (empty($polls)): ?>
                  <p>No polls available right now. Check back soon.</p>
                <?php else: ?>
                  <?php foreach($polls as $poll): ?>
                    <div class="poll-card">
                        <h4><?php echo e($poll['question']); ?></h4>
                        <div class="poll-options">
                            <?php
                            $options = json_decode($poll['options'], true);
                            if (!is_array($options)) $options = [];
                            foreach($options as $index => $option):
                            ?>
                            <button class="poll-option" onclick="votePoll(<?php echo (int)$poll['id']; ?>, <?php echo (int)$index; ?>)">
                                <?php echo e($option); ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <p class="poll-votes">Total votes: <?php echo (int)$poll['total_votes']; ?></p>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fan Wall Section -->
        <div class="fan-wall-section" style="margin-top: 3rem;">
            <h3>Fan Wall</h3>
            <div class="fan-submissions-grid">
                <?php if (empty($submissions)): ?>
                  <p>No fan submissions approved yet. Submit your work!</p>
                <?php else: ?>
                  <?php foreach($submissions as $submission): ?>
                    <div class="fan-submission-card">
                        <div class="submission-header">
                            <h4><?php echo e($submission['title']); ?></h4>
                            <span class="submission-author">by <?php echo e($submission['author_name']); ?></span>
                        </div>
                        <div class="submission-content">
                            <?php if($submission['type'] === 'image'): ?>
                            <img src="<?php echo e($submission['content']); ?>" alt="Fan submission" class="submission-image" style="max-width:100%;height:auto;border-radius:8px;">
                            <?php else: ?>
                            <p><?php echo e($submission['content']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="submission-meta">
                            <span class="submission-date"><?php echo e(date('M d, Y', strtotime($submission['created_at']))); ?></span>
                            <span class="submission-likes">❤️ <?php echo (int)$submission['likes']; ?></span>
                        </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Poll Modal -->
<div class="modal" id="pollModal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closePollModal()">&times;</span>
        <div id="pollContent"></div>
    </div>
</div>

<!-- Submission Modal -->
<div class="modal" id="submissionModal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeSubmissionModal()">&times;</span>
        <h3>Submit Your Creative Work</h3>
        <form id="submissionForm" onsubmit="submitCreativeWork(event)">
            <div class="form-group">
                <label for="submissionTitle">Title</label>
                <input type="text" id="submissionTitle" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="submissionType">Type</label>
                <select id="submissionType" class="form-control" required>
                    <option value="">Select type...</option>
                    <option value="text">Text/Poetry</option>
                    <option value="image">Artwork/Image (URL)</option>
                    <option value="video">Video Link</option>
                </select>
            </div>
            <div class="form-group">
                <label for="submissionContent">Content</label>
                <textarea id="submissionContent" class="form-control" placeholder="Paste your text, image URL, or video link here..." required></textarea>
            </div>
            <div class="form-group">
                <label for="submissionAuthor">Your Name</label>
                <input type="text" id="submissionAuthor" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<script>
function showPoll() { document.getElementById('pollModal').style.display = 'block'; /* you can load a richer poll UI here */ }
function closePollModal(){ document.getElementById('pollModal').style.display = 'none'; }
function showSubmission(){ document.getElementById('submissionModal').style.display = 'block'; }
function closeSubmissionModal(){ document.getElementById('submissionModal').style.display = 'none'; }
function showFanWall(){ document.querySelector('.fan-wall-section').scrollIntoView({ behavior: 'smooth' }); }

function votePoll(pollId, optionIndex) {
    fetch('ajax/vote_poll.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ poll_id: pollId, option_index: optionIndex })
    }).then(r=>r.json()).then(data=>{
        if(data.success){ alert('Vote submitted successfully!'); location.reload(); } else alert(data.error || 'Vote failed');
    }).catch(()=>alert('Network error'));
}

function submitCreativeWork(event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('title', document.getElementById('submissionTitle').value);
    formData.append('type', document.getElementById('submissionType').value);
    formData.append('content', document.getElementById('submissionContent').value);
    formData.append('author', document.getElementById('submissionAuthor').value);
    fetch('ajax/submit_creative_work.php', { method:'POST', body: formData })
      .then(r=>r.json()).then(data=>{
        if(data.success){ alert('Submission received! It will be reviewed.'); closeSubmissionModal(); document.getElementById('submissionForm').reset(); } else alert(data.error || 'Submission failed');
      }).catch(()=>alert('Network error'));
}

function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail').value;
    if(!email) { alert('Please enter your email'); return; }
    fetch('ajax/subscribe_newsletter.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ email: email })
    }).then(r=>r.json()).then(data=>{
        if(data.success){ alert('Subscribed!'); document.getElementById('newsletterEmail').value=''; } else alert(data.error || 'Subscribe failed');
    }).catch(()=>alert('Network error'));
}
</script>

<?php include 'footer.php'; ?>