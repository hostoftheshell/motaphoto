function setupContactOverlay() {
    const contactButtons = document.querySelectorAll('.single-contact__btn, .menu-contact__btn');
    const contactOverlay = document.querySelector('.contact-overlay');
    const closeButton = document.querySelector('.close-btn');

    function toggleContactOverlay() {
        if (contactOverlay.classList.contains('hidden')) {
            contactOverlay.classList.remove('hidden');
            contactOverlay.classList.add('fade-in');
        } else {
            contactOverlay.classList.remove('fade-in');
            contactOverlay.classList.add('fade-out');
            contactOverlay.addEventListener('animationend', () => {
                contactOverlay.classList.add('hidden');
                contactOverlay.classList.remove('fade-out');
            }, { once: true });
        }
    }

    contactButtons.forEach(button => {
        button.addEventListener('click', toggleContactOverlay);
    });

    closeButton.addEventListener('click', toggleContactOverlay);

    contactOverlay.addEventListener('click', (event) => {
        if (event.target === contactOverlay) {
            toggleContactOverlay();
        }
    });

    document.addEventListener('wpcf7mailsent', function () {
        setTimeout(toggleContactOverlay, 1000);
    }, false);
}

document.addEventListener('DOMContentLoaded', setupContactOverlay);