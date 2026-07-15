/**
 * Investor brief — interactions (Calm Intelligence redesign).
 *
 * Loaded only on page-investor-brief.php. Handles:
 *   - dataLayer click tracking (elements with [data-event])
 *   - smooth scroll for [data-scroll-to] buttons (84px sticky-header offset)
 *   - consent checkbox gating the submit button
 *   - form submission UI: swaps the form card for the success card
 *
 * TODO(backend): the form is intentionally NOT wired to any backend yet.
 * On submit it only shows the success card. When the backend is ready,
 * collect the FormData in the submit handler below and POST it before
 * showing the success state.
 */

(function () {
    'use strict';

    function track(eventName, payload) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(Object.assign({ event: eventName }, payload || {}));
    }

    // Click tracking for anything tagged data-event.
    document.addEventListener('click', function (event) {
        var target = event.target.closest('[data-event]');
        if (!target) return;
        track(target.getAttribute('data-event'), {
            text: target.textContent.trim(),
        });
    });

    // Smooth scroll with an offset for the 68px sticky header.
    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-scroll-to]');
        if (!trigger) return;
        var el = document.getElementById(trigger.getAttribute('data-scroll-to'));
        if (!el) return;
        var top = el.getBoundingClientRect().top + window.scrollY - 84;
        window.scrollTo({ top: top, behavior: 'smooth' });
    });

    // Request-materials form: consent gates submit; submit shows the
    // success card (no network call — backend comes later, see TODO above).
    var form = document.getElementById('investorForm');
    var consent = document.getElementById('investorConsent');
    var submit = document.getElementById('investorSubmit');
    var formCard = document.getElementById('investorFormCard');
    var successCard = document.getElementById('investorFormSuccess');

    if (form && consent && submit) {
        consent.addEventListener('change', function () {
            submit.disabled = !consent.checked;
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            if (!consent.checked) return;
            if (formCard) formCard.hidden = true;
            if (successCard) successCard.hidden = false;
            track('investor_request_submitted');
        });
    }
})();
