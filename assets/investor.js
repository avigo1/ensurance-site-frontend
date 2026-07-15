/**
 * Investor brief — interactions (Calm Intelligence redesign).
 *
 * Loaded only on page-investor-brief.php. Handles:
 *   - dataLayer click tracking (elements with [data-event])
 *   - smooth scroll for [data-scroll-to] buttons (84px sticky-header offset)
 *   - consent checkbox gating the submit button
 *   - form submission: POSTs JSON to the Make webhook, then swaps the form
 *     card for the success card (error message + re-enable on failure)
 *
 * The payload carries the new field names PLUS legacy aliases (full_name,
 * firm_or_company, relevant_background, investment_relevance) so the Make
 * scenario built for the previous form keeps receiving mapped data without
 * edits.
 */

(function () {
    'use strict';

    var WEBHOOK_URL = 'https://hook.us2.make.com/2jpysdrhod42h99hi1hg3xdqrt1bvv4t';

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

    // Request-materials form.
    var form = document.getElementById('investorForm');
    var consent = document.getElementById('investorConsent');
    var submit = document.getElementById('investorSubmit');
    var formCard = document.getElementById('investorFormCard');
    var successCard = document.getElementById('investorFormSuccess');
    var message = document.getElementById('investorFormMessage');

    if (!form || !consent || !submit) return;

    var SUBMIT_LABEL_DEFAULT = submit.textContent.trim();
    var SUBMIT_LABEL_SENDING = 'Sending…';

    consent.addEventListener('change', function () {
        submit.disabled = !consent.checked;
    });

    function setError(text) {
        if (!message) return;
        message.textContent = text;
        message.hidden = !text;
    }

    function setSending(isSending) {
        submit.disabled = isSending || !consent.checked;
        submit.textContent = isSending ? SUBMIT_LABEL_SENDING : SUBMIT_LABEL_DEFAULT;
    }

    function payload() {
        var data = {};
        new FormData(form).forEach(function (value, key) {
            data[key] = value;
        });
        data.consent = consent.checked;
        // Legacy aliases — the existing Make scenario maps these keys.
        data.full_name = data.name || '';
        data.firm_or_company = data.firm || '';
        data.relevant_background = data.background || '';
        data.investment_relevance = data.message || '';
        data.submitted_at = new Date().toISOString();
        data.page_url = window.location.href;
        return data;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!consent.checked) return;

        setError('');
        setSending(true);
        track('investor_request_submit_attempt');

        fetch(WEBHOOK_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload()),
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Webhook responded with ' + response.status);
            }
            if (formCard) formCard.hidden = true;
            if (successCard) successCard.hidden = false;
            track('investor_request_submitted');
        }).catch(function (err) {
            setError('Something went wrong sending your request. Please try again or contact us directly.');
            track('investor_request_error', { reason: String((err && err.message) || err) });
        }).then(function () {
            setSending(false);
        });
    });
})();
