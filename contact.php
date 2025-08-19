<?php include 'header.php'; ?>
<link rel="stylesheet" href="assets/css/styles.css" type="text/css">
<section id="contact" class="section active">
    <div class="container">
        <h2 class="section-title">Contact Us</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; max-width: 1000px; margin: 0 auto;">
            <div class="about-card">
                <h3>Get in Touch</h3>
                <form onsubmit="submitContact(event)" id="contactForm">
                    <div class="form-group">
                        <label for="contactName">Name</label>
                        <input type="text" id="contactName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contactEmail">Email</label>
                        <input type="email" id="contactEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contactSubject">Subject</label>
                        <input type="text" id="contactSubject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contactMessage">Message</label>
                        <textarea id="contactMessage" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                </form>
            </div>
            <div class="about-card">
                <h3>Contact Information</h3>
                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--gold); margin-bottom: 0.5rem;">General Inquiries</h4>
                    <p>📧 info@saadanfilms.com</p>
                    <p>📞 +1 (555) 123-FILM</p>
                </div>
                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--gold); margin-bottom: 0.5rem;">Talent & Casting</h4>
                    <p>📧 casting@saadanfilms.com</p>
                    <p>📞 +1 (555) 123-CAST</p>
                </div>
                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--gold); margin-bottom: 0.5rem;">Press & Media</h4>
                    <p>📧 press@saadanfilms.com</p>
                </div>
                <div>
                    <h4 style="color: var(--gold); margin-bottom: 0.5rem;">Follow Us</h4>
                    <div class="social-links">
                        <a href="#" class="social-link">📘</a>
                        <a href="#" class="social-link">📷</a>
                        <a href="#" class="social-link">🐦</a>
                        <a href="#" class="social-link">🎬</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function submitContact(event) {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('action', 'submit_contact');
    formData.append('name', document.getElementById('contactName').value);
    formData.append('email', document.getElementById('contactEmail').value);
    formData.append('subject', document.getElementById('contactSubject').value);
    formData.append('message', document.getElementById('contactMessage').value);

    fetch('ajax/submit_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Message sent successfully! We will get back to you soon.');
            document.getElementById('contactForm').reset();
        } else {
            alert('Error sending message: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending message. Please try again.');
    });
}
</script>

<?php include 'footer.php'; ?>