document.addEventListener('DOMContentLoaded', () => {
    const mainContainer = document.querySelector('.main-container');
    const siteHeader = document.querySelector('.site-header');
    const menuButton = document.querySelector('.menu__button'); // Selects the menu button
    const menuContent = document.querySelector('#primary-navigation'); // Selects the menu content
    const menuLinks = document.querySelectorAll('#primary-navigation .main-menu .menu-item'); // Selects all menu links

    function toggleMenu() {
        const isOpen = menuContent.classList.contains('menu-active');

        if (isOpen) {
            // Trigger the closing animation
            menuContent.classList.add('menu-closing');

            // Once the closing animation starts, remove the overlay
            menuContent.addEventListener('animationstart', function () {
                mainContainer.classList.remove('overlay-active');
            }, { once: true });

            // After the animation ends, clean up classes
            menuContent.addEventListener('animationend', function () {
                menuContent.classList.remove('menu-active');
                menuContent.classList.remove('menu-closing');
                siteHeader.classList.remove('nav-active');
                
            }, { once: true });

        } else {
            // Add the overlay first
            mainContainer.classList.add('overlay-active');

            // Trigger the opening animation
            menuContent.classList.add('menu-active');
            siteHeader.classList.add('nav-active');
            // Optionally, you can clean up or add additional actions after the opening animation ends
            siteHeader.addEventListener('animationend', function () {
                mainContainer.classList.remove('overlay-active');
            }, { once: true });
        }

        // Toggle the 'menu-active' class on the menu button
        menuButton.classList.toggle('menu-active');
    }

    // Attach the toggleMenu function to the menu button click event
    menuButton.addEventListener('click', toggleMenu);

    // Attach the toggleMenu function to each menu link to close the menu when a link is clicked
    menuLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            if (menuContent.classList.contains('menu-active')) {
                toggleMenu();
            }
        });
    });
});



// Fonction d'animation pour l'ouverture du formulaire de contact
document.addEventListener('DOMContentLoaded', () => {
    const contactButtons = document.querySelectorAll('.single-contact__btn, .menu-contact__btn'); // Select all contact buttons
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

    // Event listener for each contact button to open the overlay
    contactButtons.forEach(button => {
        button.addEventListener('click', toggleContactOverlay);
    });

    // Event listener for the close button to close the overlay
    closeButton.addEventListener('click', toggleContactOverlay);

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






document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('a[data-thumbnail]');
    const previewContainer = document.querySelector('.post-thumbnail-preview');
    const preview = previewContainer.querySelector('img');

    // Initialize preview container styles
    previewContainer.style.opacity = '0';          // Start as invisible
    previewContainer.style.visibility = 'hidden';  // Hidden by default
    previewContainer.style.transition = 'opacity 0.3s ease'; // Smooth fade-in/out

    links.forEach(link => {
        link.addEventListener('mouseover', function () {
            const thumbnailUrl = this.getAttribute('data-thumbnail');
            preview.src = thumbnailUrl;
            previewContainer.style.opacity = '1';      // Fade in the preview
            previewContainer.style.visibility = 'visible'; // Make it visible
        });

        link.addEventListener('mouseout', function () {
            previewContainer.style.opacity = '0';      // Fade out the preview
            previewContainer.style.visibility = 'hidden'; // Hide it after fade out
        });
    });
});