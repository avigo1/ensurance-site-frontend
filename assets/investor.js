/**
 * Investor brief — interactions.
 *
 * Loaded only on page-investor-brief.php. Handles:
 *   - dataLayer click tracking (elements with [data-track])
 *   - scroll-depth tracking (25 / 50 / 75 / 90 %)
 *   - form submission to a Make webhook (POST JSON)
 */

(function () {
    // Replace with the live Make webhook URL before publishing.
    // Until set, the form will surface an error rather than silently succeed.
    var WEBHOOK_URL = 'https://hook.us2.make.com/2jpysdrhod42h99hi1hg3xdqrt1bvv4t';

    var SUBMIT_LABEL_DEFAULT = 'Submit request';
    var SUBMIT_LABEL_SENDING = 'Sending…';

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

    var submitButton = form.querySelector('button[type="submit"]');

    function setMessage(text, isError) {
        message.textContent = text;
        message.classList.toggle('error', !!isError);
    }

    function setSubmitting(isSubmitting) {
        if (!submitButton) return;
        submitButton.disabled = isSubmitting;
        submitButton.textContent = isSubmitting ? SUBMIT_LABEL_SENDING : SUBMIT_LABEL_DEFAULT;
    }

    function serializeForm() {
        var data = {};
        new FormData(form).forEach(function (value, key) {
            data[key] = value;
        });
        data.submitted_at = new Date().toISOString();
        data.page_url = window.location.href;
        return data;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        setMessage('', false);

        if (!form.checkValidity()) {
            setMessage('Please complete the required fields before submitting.', true);
            form.reportValidity();
            track('investor_brief_form_validation_error');
            return;
        }

        // Honeypot — silently drop if filled.
        if (form.company_site && form.company_site.value) return;

        if (!WEBHOOK_URL || WEBHOOK_URL.indexOf('REPLACE_WITH_') === 0) {
            setMessage('Form is not configured yet. Please contact us directly.', true);
            track('investor_brief_form_misconfigured');
            return;
        }

        track('investor_brief_form_submit');
        setSubmitting(true);

        fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(serializeForm()),
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Webhook responded with ' + response.status);
            }
            setMessage('Request received. We will review and follow up if aligned.', false);
            form.reset();
            track('investor_brief_form_success');
        }).catch(function (err) {
            setMessage('Something went wrong sending your request. Please try again or contact us directly.', true);
            track('investor_brief_form_error', { reason: String(err && err.message || err) });
        }).then(function () {
            setSubmitting(false);
        });
    });
})();
