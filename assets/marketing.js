/**
 * marketing.js — Ensurance Site Frontend
 * Loaded only on marketing pages. Vanilla JS, no jQuery.
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // 1. MOBILE NAV TOGGLE
    // Hamburger button collapses/expands the primary nav on small screens.
    // =========================================================================

    const mobileToggle = document.querySelector('.site-header__mobile-toggle');
    const primaryNav   = document.getElementById('primary-nav');

    if (mobileToggle && primaryNav) {
        const closeNav = function () {
            mobileToggle.setAttribute('aria-expanded', 'false');
            mobileToggle.setAttribute('aria-label', 'Open navigation');
            mobileToggle.classList.remove('is-open');
            primaryNav.classList.remove('is-open');
        };

        const openNav = function () {
            mobileToggle.setAttribute('aria-expanded', 'true');
            mobileToggle.setAttribute('aria-label', 'Close navigation');
            mobileToggle.classList.add('is-open');
            primaryNav.classList.add('is-open');
        };

        mobileToggle.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            if (isOpen) { closeNav(); } else { openNav(); }
        });

        // Tapping a link inside the open panel closes it.
        primaryNav.addEventListener('click', function (e) {
            if (e.target.closest('a')) closeNav();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && primaryNav.classList.contains('is-open')) {
                closeNav();
                mobileToggle.focus();
            }
        });
    }

    // =========================================================================
    // 2. SMOOTH SCROLL
    // =========================================================================

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // =========================================================================
    // 3. HOMEPAGE TRACKING — data-event clicks + FAQ expand
    // Pushes to window.dataLayer when present. Safe no-op otherwise.
    // FAQ tracks by `data-faq` key (never the question text — keeps PII out
    // of the analytics layer).
    // =========================================================================

    document.querySelectorAll('[data-event]').forEach(function (el) {
        el.addEventListener('click', function () {
            const eventName = el.getAttribute('data-event');
            if (eventName && window.dataLayer && Array.isArray(window.dataLayer)) {
                window.dataLayer.push({ event: eventName, page: 'home' });
            }
        });
    });

    document.querySelectorAll('.faq-list details').forEach(function (item) {
        item.addEventListener('toggle', function () {
            if (item.open && window.dataLayer && Array.isArray(window.dataLayer)) {
                window.dataLayer.push({
                    event: 'faq_expand',
                    page: 'home',
                    faq_key: item.getAttribute('data-faq') || 'faq_item'
                });
            }
        });
    });

});
