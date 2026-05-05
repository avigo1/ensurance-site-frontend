/**
 * marketing.js — Ensurance Site Frontend
 *
 * Loaded only on marketing pages. Vanilla JS only — no jQuery dependency.
 *
 * SECTIONS:
 *   1. Mobile nav toggle
 *   2. Smooth scroll
 *   3. Component scripts (add per-component as built)
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // 1. MOBILE NAV TOGGLE
    // =========================================================================

    const toggle = document.querySelector('.site-header__mobile-toggle');
    const nav    = document.querySelector('.site-header__nav');
    const cta    = document.querySelector('.site-header__cta');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!isOpen));
            nav.classList.toggle('is-open');
            if (cta) cta.classList.toggle('is-open');
        });
    }

    // =========================================================================
    // 2. SMOOTH SCROLL
    // Handles anchor links like <a href="#section"> scrolling smoothly
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
    // 3. COMPONENT SCRIPTS
    // Add component-specific JS here as components are built.
    // =========================================================================

});
