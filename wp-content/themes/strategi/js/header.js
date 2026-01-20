(function() {
    'use strict';

    // Get elements
    const toggle = document.querySelector('[data-element="menu-toggle"]');
    const menu = document.querySelector('[data-element="mobile-menu"]');
    const body = document.body;

    if (!toggle || !menu) return;

    /**
     * Toggle mobile menu state
     */
    function toggleMenu() {
        const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

        toggle.setAttribute('aria-expanded', String(!isExpanded));
        menu.setAttribute('aria-hidden', String(isExpanded));

        // Prevent body scroll when menu is open
        body.style.overflow = isExpanded ? '' : 'hidden';
    }

    /**
     * Close mobile menu
     */
    function closeMenu() {
        toggle.setAttribute('aria-expanded', 'false');
        menu.setAttribute('aria-hidden', 'true');
        body.style.overflow = '';
    }

    // Toggle on button click
    toggle.addEventListener('click', toggleMenu);

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            closeMenu();
            toggle.focus();
        }
    });

    // Close when clicking on backdrop
    menu.addEventListener('click', (e) => {
        if (e.target === menu) closeMenu();
    });

    // Close on resize to desktop
    let resizeTimer;
    const mediaQuery = window.matchMedia('(min-width: 48rem)');

    // Modern API: matchMedia.addEventListener
    mediaQuery.addEventListener('change', (e) => {
        if (e.matches) closeMenu();
    });

    /**
     * Desktop megamenu keyboard navigation
     */
    const dropdownLinks = document.querySelectorAll('.has-dropdown > a');

    dropdownLinks.forEach((link) => {
        const parent = link.closest('li');

        // Open on focus
        link.addEventListener('focus', () => {
            link.setAttribute('aria-expanded', 'true');
        });

        // Close when focus leaves entirely
        parent.addEventListener('focusout', (e) => {
            // Use modern relatedTarget to check focus destination
            if (!parent.contains(e.relatedTarget)) {
                link.setAttribute('aria-expanded', 'false');
            }
        });

        // Handle click/touch for opening (not navigation)
        link.addEventListener('click', (e) => {
            const isExpanded = link.getAttribute('aria-expanded') === 'true';

            if (!isExpanded && window.innerWidth >= 768) {
                e.preventDefault();
                link.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // Close all dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.has-dropdown')) {
            dropdownLinks.forEach((link) => {
                link.setAttribute('aria-expanded', 'false');
            });
        }
    });

    /**
     * Progressive enhancement: Intersection Observer for animations
     */
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                // Can add scroll-triggered animations here
            });
        });

        // Observer implementation ready for future enhancements
    }

})();
