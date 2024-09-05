function setupMenuToggle() {
    const mainContainer = document.querySelector('.main-container');
    const siteHeader = document.querySelector('.site-header');
    const menuButton = document.querySelector('.menu__button');
    const menuContent = document.querySelector('#primary-navigation');
    const menuLinks = document.querySelectorAll('#primary-navigation .main-menu .menu-item');

    function toggleMenu() {
        const isOpen = menuContent.classList.contains('menu-active');

        if (isOpen) {
            menuContent.classList.add('menu-closing');

            menuContent.addEventListener('animationstart', function () {
                mainContainer.classList.remove('overlay-active');
            }, { once: true });

            menuContent.addEventListener('animationend', function () {
                menuContent.classList.remove('menu-active', 'menu-closing');
                siteHeader.classList.remove('nav-active');
            }, { once: true });

        } else {
            mainContainer.classList.add('overlay-active');
            menuContent.classList.add('menu-active');
            siteHeader.classList.add('nav-active');

            siteHeader.addEventListener('animationend', function () {
                mainContainer.classList.remove('overlay-active');
            }, { once: true });
        }

        menuButton.classList.toggle('menu-active');
    }

    menuButton.addEventListener('click', toggleMenu);

    menuLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            if (menuContent.classList.contains('menu-active')) {
                toggleMenu();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', setupMenuToggle);
