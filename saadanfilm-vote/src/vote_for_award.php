<?php
session_start();
require_once __DIR__ . '/config/database.php'; // sets $conn (mysqli)
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ensure nominees table exists
$res = $conn->query("SHOW TABLES LIKE 'nominees'");
if (!$res || $res->num_rows === 0) die("Nominees table missing. Run schema.");

// detect columns and build safe SELECT
$allowed = ['id','name','bio','headshot','films','votes_count','created_at'];
$existing = [];
$colRes = $conn->query("SHOW COLUMNS FROM nominees");
if ($colRes) { while ($col = $colRes->fetch_assoc()) $existing[] = $col['Field']; }
$selectCols = array_values(array_intersect($allowed, $existing));
if (!in_array('id', $selectCols) || !in_array('name', $selectCols)) die("Nominees table must contain at least 'id' and 'name'.");

// fetch nominees
$sql = "SELECT " . implode(', ', $selectCols) . " FROM nominees ORDER BY votes_count DESC, name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$r = $stmt->get_result();

$nominees = [];
while ($row = $r->fetch_assoc()) {
    if (!isset($row['bio'])) $row['bio'] = '';
    if (!isset($row['headshot'])) $row['headshot'] = '';
    if (!isset($row['films']) || $row['films'] === null) $row['films'] = '[]';
    if (!isset($row['votes_count'])) $row['votes_count'] = 0;
    $row['films'] = is_string($row['films']) ? (json_decode($row['films'], true) ?: []) : (array)$row['films'];
    $nominees[] = $row;
}
$totalVotes = 0; foreach ($nominees as $n) $totalVotes += (int)$n['votes_count'];

// CSRF
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

// voting end (adjust as needed)
$voting_end = '2025-09-15T23:59:59';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Saadan Film Company - Best Actor/Actress 2025</title>
  <link rel="stylesheet" href="assets/css/styles.css" type="text/css">
</head>
<body>
<?php include 'header.php'; ?>

<br><br>
<div class="hero-section">
  <div class="hero-content">
    <div class="company-logo">SAADAN FILM COMPANY</div>
    <h1 class="hero-title">Best Actor/Actress of the Year 2025</h1>
    <p class="hero-subtitle">Vote for your favorite performer and help us celebrate excellence in cinema</p>

    <div class="countdown-timer" aria-live="polite" role="timer">
      <div class="countdown-item"><div class="countdown-number" id="days">0</div><div class="countdown-label">Days</div></div>
      <div class="countdown-item"><div class="countdown-number" id="hours">0</div><div class="countdown-label">Hours</div></div>
      <div class="countdown-item"><div class="countdown-number" id="minutes">0</div><div class="countdown-label">Minutes</div></div>
      <div class="countdown-item"><div class="countdown-number" id="seconds">0</div><div class="countdown-label">Seconds</div></div>
    </div>
  </div>
</div>

<section class="voting-section">
  <h2 class="section-title">Cast Your Vote</h2>

  <div id="nomineesGrid" class="nominees-grid" aria-live="polite">
    <?php foreach($nominees as $nom): 
      $pct = $totalVotes>0 ? round($nom['votes_count']/$totalVotes*100,1) : 0;
    ?>
    <div class="nominee-card" data-id="<?= (int)$nom['id'] ?>">
      <div class="nominee-image"><?= e(mb_substr($nom['name'],0,1)) ?></div>
      <div class="nominee-info">
        <h3 class="nominee-name"><?= e($nom['name']) ?></h3>
        <p class="nominee-bio"><?= e(mb_strimwidth($nom['bio'],0,220,'...')) ?></p>
        <div class="vote-section">
          <div class="vote-percentage"><span class="percentage-text"><?= $pct ?>%</span><span class="vote-count"><?= (int)$nom['votes_count'] ?> votes</span></div>
          <div class="progress-bar"><div class="progress-fill" style="width:<?= $pct ?>%"></div></div>
          <button class="vote-button" data-id="<?= (int)$nom['id'] ?>">Vote for <?= e($nom['name']) ?></button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="leaderboard" style="margin-top:2rem;">
    <h3 class="leaderboard-title">🏆 Live Leaderboard</h3>
    <div id="leaderboardList">
      <table class="leaderboard-table" id="leaderboardTable">
        <thead><tr><th>#</th><th>Nominee</th><th>Votes</th><th>%</th></tr></thead>
        <tbody>
        <?php foreach($nominees as $i=>$n):
          $pct = $totalVotes>0 ? round($n['votes_count']/$totalVotes*100,1) : 0;
        ?>
          <tr data-id="<?= (int)$n['id'] ?>">
            <td><?= ($i+1) ?></td>
            <td><?= e($n['name']) ?></td>
            <td class="cell-votes"><?= (int)$n['votes_count'] ?></td>
            <td class="cell-pct"><?= $pct ?>%</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div style="margin-top:.6rem;color:var(--muted)">Total votes: <strong id="totalVotes"><?= $totalVotes ?></strong></div>
  </div>
</section>

<div class="floating-stats"><div class="total-votes">Total Votes: <span id="floatTotal"><?= $totalVotes ?></span></div></div>

<div class="share-modal" id="subscribeModal" style="display:none;">
  <div class="share-content">
    <button class="close-modal" onclick="closeSubscribe()">×</button>
    <h3 class="share-title">Subscribe to vote</h3>
    <p>Enter your email to subscribe — we'll identify your vote and send confirmation.</p>
    <input id="subEmail" type="email" placeholder="you@example.com" style="width:100%;padding:.6rem;border-radius:8px;margin-top:.6rem;">
    <div style="margin-top:.6rem;display:flex;gap:.6rem;justify-content:flex-end;">
      <button class="share-btn share-copy" id="doSubscribe">Subscribe & Vote</button>
      <button class="share-btn" onclick="closeSubscribe()">Cancel</button>
    </div>
    <div id="subMsg" style="margin-top:.6rem;color:#ffb86b"></div>
  </div>
</div>

<div class="share-modal" id="shareModal" style="display:none;">
  <div class="share-content">
    <button class="close-modal" onclick="closeShare()">×</button>
    <h3 class="share-title">Share Your Vote!</h3>
    <p>I just voted for <strong id="sharedActor"></strong> in Saadan Film Company's Best Actor/Actress of the Year!</p>
    <div class="share-buttons">
      <a id="shareFacebook" class="share-btn share-facebook" target="_blank">📘 Facebook</a>
      <a id="shareTwitter" class="share-btn share-twitter" target="_blank">🐦 Twitter</a>
      <a id="shareWhatsApp" class="share-btn share-whatsapp" target="_blank">💬 WhatsApp</a>
      <button class="share-btn share-copy" onclick="copyShare()">📋 Copy Link</button>
    </div>
    <div style="margin-top:.6rem;"><small id="shareLinkText"></small></div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
const CSRF = <?= json_encode($csrf) ?>;
const VOTING_END = <?= json_encode($voting_end) ?>;
let subscriberToken = localStorage.getItem('sf_sub_token') || null;
let pendingNomineeId = 0;
let totalVotes = <?= (int)$totalVotes ?>;

function updateCountdown(){
  const end = new Date(VOTING_END);
  const now = new Date();
  const diff = end - now;
  if (diff <= 0) {
    document.getElementById('days').textContent = 0;
    document.getElementById('hours').textContent = 0;
    document.getElementById('minutes').textContent = 0;
    document.getElementById('seconds').textContent = 0;
    return;
  }
  const days = Math.floor(diff / (1000*60*60*24));
  const hours = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
  const minutes = Math.floor((diff % (1000*60*60)) / (1000*60));
  const seconds = Math.floor((diff % (1000*60)) / 1000);
  document.getElementById('days').textContent = days;
  document.getElementById('hours').textContent = hours;
  document.getElementById('minutes').textContent = minutes;
  document.getElementById('seconds').textContent = seconds;
}
setInterval(updateCountdown, 1000);
updateCountdown();

document.addEventListener('click', function(e){
  const btn = e.target.closest('.vote-button');
  if (!btn) return;
  const nid = btn.getAttribute('data-id') || btn.dataset.id;
  if (!nid) return;
  if (!subscriberToken) { openSubscribe(nid); return; }
  doVote(nid, subscriberToken);
});

function openSubscribe(nid){ pendingNomineeId = nid; document.getElementById('subMsg').textContent=''; document.getElementById('subscribeModal').style.display='flex'; document.getElementById('subEmail').focus(); }
function closeSubscribe(){ document.getElementById('subscribeModal').style.display='none'; }
function openShare(){ document.getElementById('shareModal').style.display='flex'; }
function closeShare(){ document.getElementById('shareModal').style.display='none'; }

document.getElementById('doSubscribe').addEventListener('click', async function(){
  const email = document.getElementById('subEmail').value.trim();
  if (!email) { document.getElementById('subMsg').textContent='Enter a valid email'; return; }
  this.disabled = true;
  const fd = new FormData();
  fd.append('email', email);
  fd.append('csrf', CSRF);
  try {
    const res = await fetch('/ajax/subscribe.php', { method:'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      subscriberToken = data.token;
      localStorage.setItem('sf_sub_token', subscriberToken);
      document.getElementById('subMsg').textContent = 'Subscribed — casting your vote...';
      closeSubscribe();
      if (pendingNomineeId) doVote(pendingNomineeId, subscriberToken);
    } else {
      document.getElementById('subMsg').textContent = data.error || 'Subscription failed';
    }
  } catch (e) {
    document.getElementById('subMsg').textContent = 'Network error';
  } finally { this.disabled = false; }
});

async function doVote(nomineeId, token){
  try {
    const fd = new FormData();
    fd.append('nominee_id', nomineeId);
    fd.append('subscriber_token', token);
    fd.append('csrf', CSRF);
    const res = await fetch('/ajax/vote.php', { method:'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      document.querySelectorAll('.vote-button[data-id="'+nomineeId+'"]').forEach(b=>{ b.textContent='Voted'; b.disabled=true; });
      await pollStandings();
      const shareUrl = window.location.origin + '/vote_for_award.php?share=' + data.share_token;
      document.getElementById('shareLinkText').textContent = shareUrl;
      document.getElementById('sharedActor').textContent = data.nominee_name || '';
      document.getElementById('shareFacebook').href = 'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(shareUrl);
      const text = encodeURIComponent('I just voted for my favourite at Saadan Film! '+shareUrl);
      document.getElementById('shareTwitter').href = 'https://twitter.com/intent/tweet?text='+text;
      document.getElementById('shareWhatsApp').href = 'https://wa.me/?text='+text;
      window._sf_share_token = data.share_token;
      openShare();
    } else {
      alert(data.error || 'Vote failed');
      if (data.error && data.error.indexOf('Already') !== -1) {
        document.querySelectorAll('.vote-button[data-id="'+nomineeId+'"]').forEach(b=>{ b.textContent='Voted'; b.disabled=true; });
      }
    }
  } catch (e) {
    alert('Network error');
  }
}

function copyShare(){
  const text = document.getElementById('shareLinkText').textContent || window.location.href;
  navigator.clipboard.writeText(text).then(()=>{ alert('Link copied'); closeShare(); 
    if (window._sf_share_token) notifyShare('copy');
  });
}

function notifyShare(channel){
  if (!window._sf_share_token) return;
  const fd = new FormData();
  fd.append('share_token', window._sf_share_token);
  fd.append('channel', channel);
  fd.append('csrf', CSRF);
  fetch('/ajax/share_callback.php', { method:'POST', body: fd }).catch(()=>{});
}
['shareFacebook','shareTwitter','shareWhatsApp'].forEach(id=>{
  const el = document.getElementById(id);
  if (!el) return;
  el.addEventListener('click', function(){ setTimeout(()=> notifyShare(id.replace('share','').toLowerCase()), 800); });
});

async function pollStandings(){
  try {
    const res = await fetch('/ajax/standings.php');
    if (!res.ok) return;
    const data = await res.json();
    if (!data.success) return;
    totalVotes = parseInt(data.total_votes || 0);
    const nominees = data.nominees || [];
    nominees.forEach((n, idx) => {
      const pct = totalVotes>0 ? ((n.votes_count/totalVotes)*100).toFixed(1) : 0;
      document.querySelectorAll('.nominee-card[data-id="'+n.id+'"]').forEach(card=>{
        const pctEl = card.querySelector('.percentage-text');
        if (pctEl) pctEl.textContent = pct+'%';
        const votesEl = card.querySelector('.vote-count');
        if (votesEl) votesEl.textContent = parseInt(n.votes_count)+' votes';
        const fill = card.querySelector('.progress-fill');
        if (fill) fill.style.width = pct+'%';
      });
    });
    const tbody = document.querySelector('#leaderboardTable tbody');
    if (tbody) {
      tbody.innerHTML = nominees.map((n,i)=> {
        const pct = totalVotes>0 ? ((n.votes_count/totalVotes)*100).toFixed(1) : 0;
        return `<tr data-id="${n.id}"><td>${i+1}</td><td>${escapeHtml(n.name)}</td><td class="cell-votes">${parseInt(n.votes_count)}</td><td class="cell-pct">${pct}%</td></tr>`;
      }).join('');
    }
    const totalEl = document.getElementById('totalVotes');
    if (totalEl) totalEl.textContent = totalVotes;
    const floatEl = document.getElementById('floatTotal');
    if (floatEl) floatEl.textContent = totalVotes;
  } catch (e) {}
}
setInterval(pollStandings, 5000);
pollStandings();

function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

document.querySelectorAll('.share-modal').forEach(m=>{
  m.addEventListener('click', function(e){ if (e.target === this) this.style.display='none'; });
});
</script>
</body>
</html>