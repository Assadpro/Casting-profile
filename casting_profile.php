<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saadan Film Company Casting Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
   <style>
     
        /* Company Introduction Panel */
        .company-intro {
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 100%);
            padding: 60px 20px;
            text-align: center;
            border-bottom: 2px solid #ffd700;
            position: relative;
            overflow: hidden;
        }

        .company-intro::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffd700" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .intro-content {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .intro-title {
            font-size: 3.5em;
            background: linear-gradient(45deg, #ffd700, #ffed4e, #fff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite;
            margin-bottom: 20px;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .intro-subtitle {
            color: #ffd700;
            font-size: 1.4em;
            margin-bottom: 30px;
            font-weight: 300;
        }

        .intro-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1em;
            line-height: 1.8;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }

        .profile-header h1 {
            font-size: 4em;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite;
            margin-bottom: 15px;
            text-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
        }

        .profile-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.3em;
            font-style: italic;
        }

        .casting-badge {
            display: inline-block;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #000;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .profile-image-section {
            position: relative;
        }

        .profile-image {
            width: 100%;
            height: 500px;
            border-radius: 25px;
            object-fit: cover;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border: 4px solid #ffd700;
            transition: all 0.4s ease;
            animation: profileFloat 6s ease-in-out infinite;
        }

        @keyframes profileFloat {
            0%, 100% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-10px) rotateY(2deg); }
        }

        .profile-image:hover {
            transform: scale(1.03) rotateY(5deg);
            box-shadow: 0 30px 60px rgba(255, 215, 0, 0.3);
        }

        .profile-frame {
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            bottom: -20px;
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 35px;
            pointer-events: none;
        }

        .basic-info {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(26, 26, 46, 0.6) 100%);
            border-radius: 25px;
            padding: 40px;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 215, 0, 0.2);
            animation: slideInLeft 1s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 215, 0, 0.1);
            border-radius: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .info-item:hover {
            background: rgba(255, 215, 0, 0.2);
            transform: translateX(15px);
            border-left-color: #ffd700;
        }

        .info-item i {
            font-size: 1.5em;
            margin-right: 20px;
            color: #ffd700;
            width: 35px;
            text-align: center;
        }

        .info-item span {
            color: white;
            font-weight: 500;
            font-size: 1.1em;
        }

        .section {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(26, 26, 46, 0.6) 100%);
            border-radius: 25px;
            padding: 50px;
            margin-bottom: 40px;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 215, 0, 0.2);
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(255, 215, 0, 0.1);
            border-color: rgba(255, 215, 0, 0.4);
        }

        .section h2 {
            color: #ffd700;
            font-size: 2.2em;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .section h2 i {
            font-size: 1em;
        }

        .section p, .section li {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            font-size: 1.1em;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .skill-card {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            color: white;
            transition: all 0.4s ease;
            cursor: pointer;
            border: 2px solid rgba(255, 215, 0, 0.2);
        }

        .skill-card:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2);
            border-color: #ffd700;
        }

        .skill-card i {
            color: #ffd700;
            margin-bottom: 15px;
        }

        .skill-card h3 {
            color: #ffd700;
            margin-bottom: 10px;
        }

        .filmography-list {
            list-style: none;
        }

        .filmography-list li {
            background: rgba(255, 215, 0, 0.1);
            margin: 20px 0;
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #ffd700;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filmography-list li:hover {
            background: rgba(255, 215, 0, 0.2);
            transform: translateX(20px);
            border-left-width: 8px;
        }

        .contact-section {
            text-align: center;
            background: linear-gradient(135deg, #000000, #1a1a2e);
            color: white;
            border-radius: 25px;
            padding: 50px;
            margin-top: 50px;
            border: 3px solid #ffd700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
        }

        .contact-button {
            display: inline-block;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #000;
            padding: 18px 35px;
            border-radius: 50px;
            text-decoration: none;
            margin: 15px;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 1.1em;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .contact-button:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 215, 0, 0.4);
        }

        /* Footer Styles */
        .company-footer {
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 100%);
            padding: 60px 0 30px;
            border-top: 3px solid #ffd700;
            margin-top: 80px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .footer-section h3 {
            color: #ffd700;
            font-size: 1.5em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-section p, .footer-section li {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #ffd700;
        }

        .footer-bottom {
            text-align: center;
            padding: 30px 20px 20px;
            border-top: 1px solid rgba(255, 215, 0, 0.3);
            margin-top: 40px;
            color: rgba(255, 255, 255, 0.6);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background: rgba(255, 215, 0, 0.1);
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            color: #ffd700;
            font-size: 1.2em;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: #ffd700;
            color: #000;
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .nav-menu {
                gap: 15px;
            }
            
            .intro-title {
                font-size: 2.5em;
            }
            
            .profile-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .container {
                padding: 40px 15px;
            }
            
            .profile-header h1 {
                font-size: 2.8em;
            }
            
            .section {
                padding: 30px 20px;
            }
        }

        /* Floating particles */
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 215, 0, 0.1);
            border-radius: 50%;
            animation: floatParticle 20s linear infinite;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
   
</head>
<body>
    <div class="floating-particles"></div>
   <!-- Navigation -->
  <?php include 'header.php'; ?><!-- About Section -->


<!-- Maudhui Makuu -->
<div class="container">
    <div class="profile-header">
        <h1>NAISI BUSHIRI</h1>
        <p class="profile-subtitle">Muigizaji wa Saadan Film Company</p>
        <div class="casting-badge">
            <i class="fas fa-star"></i> Wasifu Maarufu wa Muigizaji
        </div>
    </div>
</div>
        <div class="profile-grid">
            <div class="profile-image-section">
                <div class="profile-frame"></div>
                <img src="nicky.png" alt="Naisi Bushiri" class="profile-image">
            </div>

            <div class="basic-info">
                <div class="info-item">
                    <i class="fas fa-user"></i>
                    <span><strong>Jina Kamili:</strong> Naisi Hassan Bushiri</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-signature"></i>
                    <span><strong>Jina la Kisanii:</strong> NICKY</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-ruler"></i>
                    <span><strong>Urefu:</strong> Futi 5 na inch 7</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-heart"></i>
                    <span><strong>Mtindo wa Mavazi:</strong> Business Casual</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-palette"></i>
                    <span><strong>Rangi ya Ngozi:</strong> Nyeusi</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-user-circle"></i>Bio ya Kisanii</h2>
            <p>Naisi Bushiri ni msanii anayevuka mipaka ya kawaida kwa kipaji chake cha kugiza kwa uhalisia wa juu. Ameshika nafasi tofauti zenye changamoto na kuzigeuza kuwa hadthi zinazosoba hisia na ujumbe macho. Kupitia Saadan Film Company, amendelea kung'aa kama sura mpya ya ubunifu, akileta uhalisia ndani ya kila uhusika anaohohoka.</p>
        </div>

        <div class="section">
            <h2><i class="fas fa-star"></i>Vipaji na Uwezo Maalum</h2>
            <div class="skills-grid">
                <div class="skill-card">
                    <i class="fas fa-language" style="font-size: 2.5em; margin-bottom: 15px;"></i>
                    <h3>Lugha</h3>
                    <p>Kiswahili, Kiingereza</p>
                </div>
                <div class="skill-card">
                    <i class="fas fa-theater-masks" style="font-size: 2.5em; margin-bottom: 15px;"></i>
                    <h3>Vipaji</h3>
                    <p>Kuigiza, Mwandishi wa stori</p>
                </div>
                <div class="skill-card">
                    <i class="fas fa-running" style="font-size: 2.5em; margin-bottom: 15px;"></i>
                    <h3>Michezo</h3>
                    <p>Kucheza Mpira wa miguu</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2><i class="fas fa-film"></i>Kazi Ndani ya Saadan</h2>
            <ul class="filmography-list">
                <li><strong>Black Demon</strong> — Uhusika wa Black Demon</li>
                <li><strong>Flash</strong> — Uhusika wa Nicky</li>
            </ul>
        </div>

        <div class="section">
            <h2><i class="fas fa-history"></i>Historia ya Msanii Ndani ya Saadan</h2>
            <p>Naisi Bushiri alijiunga rasmi na Saadan Film Company mwaka 2022. Tangu wakati huo, amekuwa sehem ya miradi mbalimbali ya kampuni, akonyesha uaminifu, nidhamu na mchango mkubwa katika kazi za ubunifu. Ameonyesha ustadi mkubwa katika kugiza na kuonyesha uwezo wa kufanya kazi kwa timu.</p>
        </div>

        <div class="contact-section">
            <h2><i class="fas fa-envelope"></i>Mawasiliano kwa Kazi</h2>
            <p style="font-size: 1.2em; margin-bottom: 30px;"><strong>Email ya Kazi:</strong> info@saadanfilm.com</p>
            <p style="margin-bottom: 30px;"><strong>NB:</strong> Tafadhali wasiliana kupitia barua pepe hii kwa kazi rasmi tu.</p>
            
            <div>
                <a href="mailto:info@saadanfilm.com" class="contact-button">
                    <i class="fas fa-envelope"></i> Tuma Barua Pepe
                </a>
                <a href="http://www.youtube.com/@saadan8505" class="contact-button" target="_blank">
                    <i class="fab fa-youtube"></i> YouTube Channel
                </a>
            </div>
        </div>
    </div>

      <!-- Footer -->
   <?php include 'footer.php'; ?>

    <script>
        // Create floating particles with company colors
        function createParticles() {
            const container = document.querySelector('.floating-particles');
            
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 4 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 15 + 15) + 's';
                
                container.appendChild(particle);
            }
        }

        // Enhanced hover effects
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Smooth scrolling for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Enhanced card interactions
            const interactiveElements = document.querySelectorAll('.skill-card, .filmography-list li, .info-item, .section');
            
            interactiveElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });
        });

        // Scroll animations with intersection observer
        const observerOptions = {
            threshold: 0.2
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.section, .profile-header, .casting-badge').forEach(section => {
            observer.observe(section);
        });
    </script>

<?php
// Casting profile — load casting call from DB and render a professional profile page
include 'header.php';
include 'config/database.php'; // must set $conn (mysqli)

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
  header('Location: casting.php');
  exit;
}

$stmt = $conn->prepare("SELECT * FROM casting_calls WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$casting = $res ? $res->fetch_assoc() : null;
if (!$casting) {
  echo '<div class="container"><div class="about-card"><h3>Not found</h3><p>Requested casting call could not be located.</p><a class="btn btn-ghost" href="casting.php">Back to Casting</a></div></div>';
  include 'footer.php';
  exit;
}

// format values
$project_name    = $casting['project_name'] ?? 'Untitled Project';
$role_title      = $casting['role_title'] ?? 'Role';
$project_type    = $casting['project_type'] ?? 'Film';
$role_description= $casting['role_description'] ?? $casting['description'] ?? '';
$description     = $casting['description'] ?? '';
$deadline        = $casting['deadline'] ? date('F d, Y', strtotime($casting['deadline'])) : 'TBA';
$location        = $casting['location'] ?? 'TBA';
$status          = $casting['status'] ?? 'active';
?>
<section class="section">
  <div class="container">
    <div class="about-card" style="padding:1.25rem;">
      <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-start;">
        <div style="flex:1;min-width:240px">
          <h1 style="margin:0 0 .4rem 0;color:var(--white)"><?= e($project_name) ?></h1>
          <h2 style="margin:0 0 .6rem 0;color:var(--muted);font-weight:700;"><?= e($role_title) ?></h2>
          <div style="color:var(--muted);font-size:.95rem;">
            <strong>Type:</strong> <?= e($project_type) ?> &nbsp; • &nbsp;
            <strong>Location:</strong> <?= e($location) ?> &nbsp; • &nbsp;
            <strong>Deadline:</strong> <?= e($deadline) ?> &nbsp; • &nbsp;
            <strong>Status:</strong> <?= e(ucfirst($status)) ?>
          </div>

          <div style="margin-top:1rem;">
            <a class="btn btn-primary" href="casting.php#auditionForm" id="applyNowBtn">Apply now</a>
            <a class="btn btn-ghost" href="casting.php" style="margin-left:.6rem">Back to casting list</a>
          </div>
        </div>

        <div style="width:320px;min-width:220px;">
          <div style="background:linear-gradient(135deg,var(--gold),#ff8a50);padding:1rem;border-radius:12px;text-align:center;color:#111;font-weight:700;">
            <div style="font-size:1.05rem">Role Snapshot</div>
            <div style="margin-top:.6rem;font-size:.95rem;color:#111;"><?= e(mb_strimwidth($role_description,0,200,'...')) ?></div>
          </div>

          <div style="margin-top:.8rem;background:rgba(255,255,255,0.02);padding:.7rem;border-radius:8px;border:1px solid rgba(255,255,255,0.03);">
            <div style="font-size:.9rem;color:var(--muted)"><strong>Posted:</strong> <?= e(date('M d, Y', strtotime($casting['created_at'] ?? date('Y-m-d')))) ?></div>
            <div style="font-size:.9rem;color:var(--muted)"><strong>Open slots:</strong> <?= e($casting['slots'] ?? 'N/A') ?></div>
          </div>
        </div>
      </div>

      <hr style="border-color:rgba(255,255,255,0.03);margin:1rem 0;">

      <div style="display:grid;grid-template-columns:1fr 360px;gap:1rem;">
        <div>
          <h3 style="margin-top:0;color:var(--white)">Role Description</h3>
          <p style="color:var(--muted);line-height:1.6"><?= nl2br(e($role_description)) ?></p>

          <h3 style="margin-top:1rem;color:var(--white)">Project Details</h3>
          <p style="color:var(--muted);line-height:1.6"><?= nl2br(e($description)) ?></p>
        </div>

        <aside style="min-width:240px;">
          <div class="about-card" style="padding:1rem;">
            <h4 style="margin:0 0 .5rem 0;color:var(--white)">How to Apply</h4>
            <ol style="color:var(--muted);padding-left:1.1rem;">
              <li>Click "Apply now" — you will be taken to the audition form.</li>
              <li>Complete bio, contact details and include demo reel / portfolio link.</li>
              <li>Shortlisted performers will be contacted by email.</li>
            </ol>
            <div style="margin-top:.75rem;">
              <a class="btn btn-primary" href="casting.php#auditionForm" id="applyNowBtn2">Apply for this role</a>
            </div>
          </div>

          <div class="about-card" style="padding:1rem;margin-top:1rem;">
            <h4 style="margin:0 0 .5rem 0;color:var(--white)">Contact</h4>
            <p style="color:var(--muted);margin:0">For casting enquiries: <a href="mailto:casting@saadanfilm.com">casting@saadanfilm.com</a></p>
          </div>
        </aside>
      </div>
    </div>
  </div>
</section>

<script>
// prefill audition form on casting.php when user clicks Apply links
(function(){
  const id = <?= (int)$id ?>;
  function openAndPrefill(btn){
    btn.addEventListener('click', function(e){
      // allow normal navigation to casting.php anchor, but set a storage key so casting.php can read and prefill
      try { localStorage.setItem('prefill_casting_id', id); } catch(e){}
      // small delay to preserve anchor behavior in browsers
      setTimeout(()=>{ window.location = 'casting.php#auditionForm'; }, 50);
      e.preventDefault();
    });
  }
  const b1 = document.getElementById('applyNowBtn');
  const b2 = document.getElementById('applyNowBtn2');
  if (b1) openAndPrefill(b1);
  if (b2) openAndPrefill(b2);
})();
</script>

<?php include 'footer.php'; ?>