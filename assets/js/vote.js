// filepath: saadanfilm/assets/js/vote.js
document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.vote-button');
    const totalVotesElement = document.getElementById('totalVotes');
    const shareModal = document.getElementById('shareModal');
    const sharedActor = document.getElementById('sharedActor');
    const shareFacebook = document.getElementById('shareFacebook');
    const shareTwitter = document.getElementById('shareTwitter');
    const shareWhatsApp = document.getElementById('shareWhatsApp');
    const subscribeModal = document.getElementById('subscribeModal');
    const subscribeButton = document.getElementById('subscribeButton');
    const emailInput = document.getElementById('subscribeEmail');
    let totalVotes = 0;

    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const nomineeId = this.dataset.nomineeId;
            showSubscribeModal(nomineeId);
        });
    });

    function showSubscribeModal(nomineeId) {
        subscribeModal.style.display = 'flex';
        subscribeButton.onclick = function() {
            const email = emailInput.value;
            if (validateEmail(email)) {
                subscribeUser(email, nomineeId);
            } else {
                alert('Please enter a valid email address.');
            }
        };
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function subscribeUser(email, nomineeId) {
        fetch('ajax/vote.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, nomineeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Thank you for subscribing! You can now vote.');
                vote(nomineeId);
                subscribeModal.style.display = 'none';
            } else {
                alert('Subscription failed. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function vote(nomineeId) {
        fetch('ajax/vote.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nomineeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                totalVotes++;
                totalVotesElement.textContent = totalVotes;
                showShareModal(data.nomineeName);
            } else {
                alert('Vote failed. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function showShareModal(actorName) {
        sharedActor.textContent = actorName;
        shareModal.style.display = 'flex';
        const text = `I just voted for ${actorName} in Saadan Film Company's Best Actor/Actress of the Year! Vote now: ${window.location.href}`;
        const encodedText = encodeURIComponent(text);
        const url = encodeURIComponent(window.location.href);

        shareFacebook.href = `https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${encodedText}`;
        shareTwitter.href = `https://twitter.com/intent/tweet?text=${encodedText}`;
        shareWhatsApp.href = `https://wa.me/?text=${encodedText}`;
    }

    document.getElementById('closeShareModal').onclick = function() {
        shareModal.style.display = 'none';
    };
});