// Fonction d'animation pour l'ouverture du menu de navigation principale sur tablette et mobile
document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.querySelector('.menu__button'); // Sélectionne le bouton du menu
    const menuContent = document.querySelector('#primary-navigation'); // Sélectionne le contenu du menu
    const menuLinks = document.querySelectorAll('#primary-navigation .main-menu .menu-item'); // Sélectionne tous les liens du menu
    console.log(menuLinks);
    function toggleMenu() {
        // Vérifie si le menu est actuellement ouvert
        const isOpen = menuContent.classList.contains('menu-active');

        if (isOpen) {
            
            // Ajoute la classe 'menu-closing' pour déclencher l'animation de fermeture
            menuContent.classList.add('menu-closing');
            // Retire 'menu-active' après la fin de l'animation
            menuContent.addEventListener('animationend', function () {
                menuContent.classList.remove('menu-active');
                menuContent.classList.remove('menu-closing');

                
            }, { once: true });
        } else {
            // Ajoute 'menu-active' pour ouvrir le menu
            menuContent.classList.add('menu-active');
        }

        // Bascule la classe 'menu-active' sur le bouton
        menuButton.classList.toggle('menu-active');
    }

    // Attache l'événement de clic au bouton du menu
    menuButton.addEventListener('click', toggleMenu);

    // Attache l'événement de clic à chaque lien du menu pour fermer le menu lorsqu'un lien est cliqué
    menuLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            // Ferme le menu seulement s'il est actuellement ouvert
            if (menuContent.classList.contains('menu-active')) {
                toggleMenu();
            }
        });
    });
});


// Fonction d'animation pour l'ouverture du formulaire de contact
document.addEventListener('DOMContentLoaded', () => {
    const contactButton = document.querySelector('.contact-btn');
    const contactOverlay = document.querySelector('.contact-overlay');
    const closeButton = document.querySelector('.close-btn');
    
    // Function to toggle the visibility of the contact overlay with animation
    function toggleContactOverlay() {
        if (contactOverlay.classList.contains('hidden')) {
            contactOverlay.classList.remove('hidden');
            contactOverlay.classList.add('fade-in');
        } else {
            contactOverlay.classList.remove('fade-in');
            contactOverlay.classList.add('fade-out');
            // Wait for the animation to finish before adding the 'hidden' class
            contactOverlay.addEventListener('animationend', () => {
                contactOverlay.classList.add('hidden');
                contactOverlay.classList.remove('fade-out');
            }, { once: true });
        }
    }

    // Event listener for the contact button to open the overlay
    contactButton.addEventListener('click', toggleContactOverlay);
    closeButton.addEventListener('click', toggleContactOverlay );
    // Optional: Close the overlay when clicking outside of it
    contactOverlay.addEventListener('click', (event) => {
        if (event.target === contactOverlay) {
            toggleContactOverlay();
        }
    });
     // Event listener for form submission using wpcf7 events
    document.addEventListener('wpcf7mailsent', function () {
        // Delay the closing of the overlay by 2 seconds (2000 milliseconds)
        setTimeout(toggleContactOverlay, 1000); 
    }, false);
});



document.addEventListener('DOMContentLoaded', () => {
    const parentElements = document.querySelectorAll('.contact-overlay__header-wrapper');

    parentElements.forEach((parentElement) => {
        parentElement.childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE && !node.nodeValue.trim()) {
                node.remove();
            }
        });
    });
});