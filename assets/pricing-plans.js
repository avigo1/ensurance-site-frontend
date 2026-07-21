/* ===================================================================
   Founding Agent Access (/pricing-plans) — page interactions
   - FAQ accordion (single-open, keyboard/aria correct)
   - Scroll reveal for sections + how-it-works steps
   - Smooth in-page scroll for hero anchors, offset for sticky header
   Vanilla JS, no dependencies. Layered on top of home.js.
   =================================================================== */
(function () {
  'use strict';

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ── FAQ accordion ─────────────────────────────────────────── */
  function initAccordion() {
    var items = document.querySelectorAll('.fa-accordion .fa-acc__item');
    items.forEach(function (item) {
      var btn = item.querySelector('.fa-acc__q');
      if (!btn) return;
      btn.addEventListener('click', function () {
        var isOpen = item.classList.contains('is-open');
        if (isOpen) {
          item.classList.remove('is-open');
          btn.setAttribute('aria-expanded', 'false');
        } else {
          item.classList.add('is-open');
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }

  /* ── Smooth in-page scroll (accounts for sticky header height) ─ */
  function initAnchors() {
    var header = document.querySelector('.site-header');
    document.querySelectorAll('.fa-page a[href^="#"]').forEach(function (a) {
      var id = a.getAttribute('href').slice(1);
      if (!id) return;
      a.addEventListener('click', function (e) {
        var target = document.getElementById(id);
        if (!target) return;
        e.preventDefault();
        var offset = (header ? header.offsetHeight : 0) + 16;
        var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: reduce ? 'auto' : 'smooth' });
      });
    });
  }

  /* ── Scroll reveal ─────────────────────────────────────────── */
  function initReveal() {
    var sections = Array.prototype.slice.call(document.querySelectorAll('.fa-page .fa-section'));
    sections.forEach(function (s) { s.classList.add('fa-reveal'); });
    var steps = Array.prototype.slice.call(document.querySelectorAll('.fa-page .fa-step'));
    var targets = sections.concat(steps);
    if (!targets.length) return;

    if (reduce) {
      targets.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-in');
            io.unobserve(entry.target);
          }
        });
      }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });
      targets.forEach(function (el) { io.observe(el); });
    } else {
      targets.forEach(function (el) { el.classList.add('is-in'); });
    }

    // Failsafe: never leave content hidden if observer misses.
    setTimeout(function () {
      targets.forEach(function (el) { el.classList.add('is-in'); });
    }, 2000);
  }

  function init() {
    initAccordion();
    initAnchors();
    initReveal();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
