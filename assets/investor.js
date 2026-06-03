/**
 * Investor brief — interactions.
 *
 * Loaded only on page-investor-brief.php. Handles:
 *   - dataLayer click tracking (elements with [data-track])
 *   - scroll-depth tracking (25 / 50 / 75 / 90 %)
 *   - in-page form submission (no backend wired yet; see TODO below)
 *
 * TODO (production): wire form submission to a real backend handler
 * (WP REST endpoint, CRM webhook, or email handler). Current behavior
 * intercepts submit and shows an in-page acknowledgement only.
 */

(function () {
    var trackedDepths = new Set();

    function track(eventName, payload) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(Object.assign({ event: eventName }, payload || {}));
    }

    document.addEventListener('click', function (event) {
        var target = event.target.closest('[data-track]');
        if (!target) return;
        track(target.getAttribute('data-track'), {
            text: target.textContent.trim(),
            href: target.getAttribute('href') || null,
        });
    });

    window.addEventListener('scroll', function () {
        var documentHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (documentHeight <= 0) return;
        var depth = Math.round((window.scrollY / documentHeight) * 100);
        [25, 50, 75, 90].forEach(function (mark) {
            if (depth >= mark && !trackedDepths.has(mark)) {
                trackedDepths.add(mark);
                track('investor_brief_scroll_depth', { depth: mark });
            }
        });
    }, { passive: true });

    var form = document.getElementById('investorForm');
    var message = document.getElementById('formMessage');
    if (!form || !message) return;

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        message.classList.remove('error');

        if (!form.checkValidity()) {
            message.textContent = 'Please complete the required fields before submitting.';
            message.classList.add('error');
            form.reportValidity();
            track('investor_brief_form_validation_error');
            return;
        }

        // Honeypot — silently drop if filled.
        if (form.company_site && form.company_site.value) return;

        track('investor_brief_form_submit');
        message.textContent = 'Request received. We will review and follow up if aligned.';
        form.reset();
    });
})();
