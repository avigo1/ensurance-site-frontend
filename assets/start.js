/**
 * start.js — Ensurance /start guided intake wizard.
 *
 * Loaded only on page-start.php.
 *
 * Responsibilities:
 *   1. Toggle no-js / has-js root class so CSS knows which fallback to render.
 *   2. Reveal one wizard step at a time, with Continue / Back navigation.
 *   3. Validate the currently visible step before advancing.
 *   4. Prefill the coverage select from the ?coverage= query param
 *      (matches the behavior shown in the design preview).
 *   5. Build a review summary on the review step.
 *   6. On submit, generate a reference ID, surface the confirmation card,
 *      push a telemetry event, and redirect to /request-received.
 */

(function () {
    'use strict';

    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('has-js');

    document.addEventListener('DOMContentLoaded', function () {

        var form = document.querySelector('[data-start-form]');
        if (!form) return;

        var steps          = Array.prototype.slice.call(form.querySelectorAll('[data-step]'));
        var progressFill   = document.querySelector('[data-progress-fill]');
        var progressCurrent = document.querySelector('[data-progress-current]');
        var reviewMount    = form.querySelector('[data-review]');
        var confirmation   = document.querySelector('[data-confirmation]');
        var confirmationRef = document.querySelector('[data-confirmation-reference]');
        var confirmationLink = document.querySelector('[data-confirmation-link]');
        var redirect       = form.getAttribute('data-redirect') || '/request-received';

        var current = 0;

        // -- 1. Coverage prefill ---------------------------------------------

        var params = new URLSearchParams(window.location.search);
        var coverageParam = params.get('coverage');
        if (coverageParam) {
            var normalized = coverageParam.toLowerCase().replace(/-/g, ' ');
            var coverageSelect = form.querySelector('select[name="coverageType"]');
            if (coverageSelect) {
                Array.prototype.forEach.call(coverageSelect.options, function (option) {
                    if (option.value && option.textContent.toLowerCase().indexOf(normalized) !== -1) {
                        coverageSelect.value = option.value;
                    }
                });
            }
        }

        // -- 2. Step navigation ----------------------------------------------

        function showStep(index, opts) {
            opts = opts || {};
            steps.forEach(function (step, i) {
                var isActive = i === index;
                step.hidden = !isActive;
                step.classList.toggle('is-active', isActive);
            });
            current = index;
            updateProgress();
            if (steps[index].hasAttribute('data-step-id') && steps[index].getAttribute('data-step-id') === 'review') {
                buildReview();
            }
            if (!opts.suppressScroll) {
                var shell = document.querySelector('.start-wizard__shell');
                if (shell) shell.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            var heading = steps[index].querySelector('.start-step__title');
            if (heading) heading.setAttribute('tabindex', '-1');
            if (heading) heading.focus({ preventScroll: true });
        }

        function updateProgress() {
            var pct = Math.round(((current + 1) / steps.length) * 100);
            if (progressFill) progressFill.style.width = pct + '%';
            if (progressCurrent) progressCurrent.textContent = String(current + 1);
        }

        // -- 3. Per-step validation ------------------------------------------

        function validateStep(stepEl) {
            var errorEl = stepEl.querySelector('[data-step-error]');
            if (errorEl) {
                errorEl.hidden = true;
                errorEl.textContent = '';
            }
            var invalid = null;
            var fields = stepEl.querySelectorAll('input, select, textarea');
            Array.prototype.forEach.call(fields, function (field) {
                field.removeAttribute('aria-invalid');
                if (!field.checkValidity()) {
                    field.setAttribute('aria-invalid', 'true');
                    if (!invalid) invalid = field;
                }
            });

            // Radio groups: ensure at least one is selected when group is required.
            var radioGroups = stepEl.querySelectorAll('.start-radio-group');
            Array.prototype.forEach.call(radioGroups, function (group) {
                var radios = group.querySelectorAll('input[type="radio"]');
                if (!radios.length) return;
                var required = radios[0].required;
                if (!required) return;
                var hasChecked = Array.prototype.some.call(radios, function (r) { return r.checked; });
                if (!hasChecked && !invalid) {
                    invalid = radios[0];
                }
            });

            if (invalid) {
                if (errorEl) {
                    errorEl.textContent = 'Please complete the required fields before continuing.';
                    errorEl.hidden = false;
                }
                try { invalid.focus({ preventScroll: false }); } catch (e) { invalid.focus(); }
                return false;
            }
            return true;
        }

        // -- 4. Review summary build -----------------------------------------

        var REVIEW_LABELS = {
            coverageType:    'Coverage type',
            state:           'State',
            zip:             'ZIP code',
            reason:          'Reason',
            details:         'Request details',
            timing:          'Timing',
            supportChannel:  'Preferred contact method',
            supportTime:     'Best time to reach you',
            name:            'Full name',
            email:           'Email address',
            phone:           'Phone number'
        };

        var FIELD_TO_STEP_INDEX = (function () {
            var map = {};
            steps.forEach(function (step, i) {
                var fields = step.querySelectorAll('input[name], select[name], textarea[name]');
                Array.prototype.forEach.call(fields, function (field) {
                    if (!map.hasOwnProperty(field.name)) {
                        map[field.name] = i;
                    }
                });
            });
            return map;
        })();

        function getDisplayValue(name) {
            var radio = form.querySelector('input[type="radio"][name="' + name + '"]:checked');
            if (radio) return radio.value;
            var el = form.querySelector('[name="' + name + '"]');
            if (!el) return '';
            return (el.value || '').trim();
        }

        function buildReview() {
            if (!reviewMount) return;
            reviewMount.innerHTML = '';
            var hasAny = false;

            Object.keys(REVIEW_LABELS).forEach(function (name) {
                var value = getDisplayValue(name);
                if (!value) return;
                hasAny = true;
                var stepIndex = FIELD_TO_STEP_INDEX[name];

                var row = document.createElement('div');
                row.className = 'start-review__row';

                var label = document.createElement('span');
                label.className = 'start-review__label';
                label.textContent = REVIEW_LABELS[name];

                var val = document.createElement('span');
                val.className = 'start-review__value';
                val.textContent = value;

                row.appendChild(label);
                row.appendChild(val);

                if (typeof stepIndex === 'number') {
                    var edit = document.createElement('button');
                    edit.type = 'button';
                    edit.className = 'start-review__edit';
                    edit.textContent = 'Edit';
                    edit.setAttribute('data-event', 'start_review_edit_click');
                    edit.addEventListener('click', function () {
                        showStep(stepIndex);
                    });
                    row.appendChild(edit);
                }

                reviewMount.appendChild(row);
            });

            if (!hasAny) {
                var empty = document.createElement('p');
                empty.className = 'start-review__empty';
                empty.textContent = 'No answers yet. Step back to fill in your request details.';
                reviewMount.appendChild(empty);
            }
        }

        // -- 5. Wire up buttons ----------------------------------------------

        form.addEventListener('click', function (event) {
            var nextBtn = event.target.closest('[data-step-next]');
            var backBtn = event.target.closest('[data-step-back]');
            if (nextBtn) {
                event.preventDefault();
                var stepEl = nextBtn.closest('[data-step]');
                if (!stepEl) return;
                if (!validateStep(stepEl)) return;
                if (current < steps.length - 1) showStep(current + 1);
            } else if (backBtn) {
                event.preventDefault();
                if (current > 0) showStep(current - 1);
            }
        });

        // -- 6. Submit -------------------------------------------------------

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Validate every step to catch anything that was skipped via Edit.
            for (var i = 0; i < steps.length; i++) {
                if (!validateStep(steps[i])) {
                    showStep(i);
                    return;
                }
            }

            var formData = new FormData(form);
            var coverage = String(formData.get('coverageType') || 'insurance-request')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');

            var referenceId = 'ENS-' + Math.random().toString(36).slice(2, 8).toUpperCase();

            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'start_request_form_submit',
                coverage_type: coverage,
                request_id: referenceId
            });

            // Hide the form, show the confirmation card.
            form.hidden = true;
            var progress = document.querySelector('[data-progress]');
            if (progress) progress.hidden = true;
            if (confirmation) {
                confirmation.hidden = false;
                if (confirmationRef) confirmationRef.textContent = referenceId;
                if (confirmationLink) {
                    var url = redirect
                        + '?requestId=' + encodeURIComponent(referenceId)
                        + '&coverage=' + encodeURIComponent(coverage)
                        + '&source=start_form';
                    confirmationLink.setAttribute('href', url);
                }
                confirmation.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            setTimeout(function () {
                window.location.href = redirect
                    + '?requestId=' + encodeURIComponent(referenceId)
                    + '&coverage=' + encodeURIComponent(coverage)
                    + '&source=start_form';
            }, 1200);
        });

        // -- 7. Lightweight telemetry passthrough ----------------------------
        // Mirrors the data-event pattern used elsewhere on the marketing site.
        document.querySelectorAll('[data-event]').forEach(function (el) {
            el.addEventListener('click', function () {
                var evt = el.getAttribute('data-event');
                if (!evt) return;
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({ event: evt });
            });
        });

        // -- 8. Init ---------------------------------------------------------
        updateProgress();
    });
})();
