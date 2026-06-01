/**
 * marketing.js — Ensurance Site Frontend
 * Loaded only on marketing pages. Vanilla JS, no jQuery.
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // 1. "FOR YOU" DROPDOWN
    // =========================================================================

    const dropdownToggle = document.querySelector('.site-header__dropdown-toggle');
    const dropdown       = document.getElementById('dropdown-for-you');

    if (dropdownToggle && dropdown) {
        dropdownToggle.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!isOpen));
            dropdown.classList.toggle('is-open', !isOpen);
        });

        // Close when clicking anywhere outside the dropdown's parent <li>
        document.addEventListener('click', function (e) {
            if (!dropdownToggle.closest('.has-dropdown').contains(e.target)) {
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdown.classList.remove('is-open');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdown.classList.remove('is-open');
                dropdownToggle.focus();
            }
        });
    }

    // =========================================================================
    // 2. MOBILE NAV TOGGLE
    // =========================================================================

    const mobileToggle = document.querySelector('.site-header__mobile-toggle');
    const mobileNav    = document.getElementById('mobile-nav');

    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!isOpen));
            this.classList.toggle('is-open', !isOpen);
            mobileNav.classList.toggle('is-open', !isOpen);
            mobileNav.setAttribute('aria-hidden', String(isOpen));
            this.setAttribute('aria-label', isOpen ? 'Open menu' : 'Close menu');
        });
    }

    // =========================================================================
    // 3. SMOOTH SCROLL
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
    // 4. HOMEPAGE TRACKING — data-event clicks + FAQ expand
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
