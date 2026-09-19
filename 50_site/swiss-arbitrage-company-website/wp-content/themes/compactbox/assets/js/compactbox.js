(function () {
    'use strict';

    /**
     * CompactBox header mobile menu toggle.
     *
     * Toggles the primary navigation on small screens and updates ARIA state.
     */
    function compactboxHeaderToggle() {
        var toggle = document.querySelector('.cb-header__toggle');
        var menu = document.querySelector('.cb-header__menu-wrapper');

        if (! toggle || ! menu) {
            return;
        }

        toggle.addEventListener('click', function () {
            var isOpen = menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && menu.classList.contains('is-open')) {
                menu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768 && menu.classList.contains('is-open')) {
                menu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /**
     * CompactBox product carousel controls.
     *
     * Wires previous/next buttons to scroll the product track by one viewport
     * width. Degrades gracefully if the track or buttons are missing.
     */
    function compactboxProductCarousel() {
        var track = document.querySelector('.cb-products__track');
        var prevButton = document.querySelector('.cb-products__prev');
        var nextButton = document.querySelector('.cb-products__next');

        if (! track) {
            return;
        }

        var prefersReducedMotion = window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var scrollBehavior = prefersReducedMotion ? 'auto' : 'smooth';

        function scrollTrack(direction) {
            var delta = track.clientWidth * direction;
            track.scrollBy({
                left: delta,
                behavior: scrollBehavior,
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                scrollTrack(-1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                scrollTrack(1);
            });
        }
    }

    /**
     * CompactBox product section accordion toggles.
     *
     * Wires each .cb-product__accordion-toggle to expand/collapse its panel.
     * The [hidden] attribute and CSS handle the visual state; JS only flips
     * the boolean attribute and ARIA state.
     */
    function compactboxProductAccordion() {
        var toggles = document.querySelectorAll('.cb-product__accordion-toggle');

        if (! toggles.length) {
            return;
        }

        toggles.forEach(function (toggle) {
            var panelId = toggle.getAttribute('aria-controls');
            var panel = panelId ? document.getElementById(panelId) : null;

            if (! panel) {
                return;
            }

            toggle.addEventListener('click', function () {
                var isExpanded = toggle.getAttribute('aria-expanded') === 'true';

                toggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                panel.hidden = isExpanded;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', compactboxHeaderToggle);
    document.addEventListener('DOMContentLoaded', compactboxProductCarousel);
    document.addEventListener('DOMContentLoaded', compactboxProductAccordion);
}());
