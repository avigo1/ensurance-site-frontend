/* ===================================================================
   "Contact" (/contact) — scroll-reveal + message-form submission.

   Reveal: ported from the standalone design's App useEffect — every
   .reveal section fades/slides in once its top crosses 90% of the
   viewport. Respects prefers-reduced-motion, with a safety timeout so
   content can never get stuck behind the initial opacity:0.

   Form: validates inline (same copy as the standalone design), then
   POSTs JSON to the REST endpoint in the form's data-endpoint
   attribute (/wp-json/ensurance/v1/contact — see functions.php,
   ensurance_contact_handle). On success the card swaps to the inline
   confirmation state; "Send another message" restores a fresh form.
   The ct_elapsed field carries ms-since-load for the server time trap.

   home.js handles the rest of the chrome (mobile nav, FAQ tracking).
   =================================================================== */
(function () {
  function init() {
    var targets = [].slice.call(document.querySelectorAll(".reveal"));

    if (!targets.length) return;

    function showAll() {
      targets.forEach(function (el) {
        el.classList.add("is-in");
      });
    }

    var reduce =
      window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (reduce) {
      showAll();
      return;
    }

    var pending = targets.slice();

    function tick() {
      var trigger = window.innerHeight * 0.9;
      pending = pending.filter(function (el) {
        if (el.getBoundingClientRect().top < trigger) {
          el.classList.add("is-in");
          return false;
        }
        return true;
      });
      if (!pending.length) {
        window.removeEventListener("scroll", onScroll);
        window.removeEventListener("resize", onScroll);
        clearTimeout(fallback);
      }
    }

    var raf = 0;
    function onScroll() {
      if (raf) return;
      raf = requestAnimationFrame(function () {
        raf = 0;
        tick();
      });
    }

    // Seed once so anything already in view reveals on load.
    setTimeout(tick, 60);
    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);

    // Safety net: never leave content hidden if scroll/resize never fire.
    var fallback = setTimeout(showAll, 1800);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

/* ── Message form ─────────────────────────────────────────────────── */
(function () {
  var loadedAt = Date.now();

  function init() {
    var form = document.querySelector(".ct-form");
    if (!form || !form.getAttribute("data-endpoint")) return;

    var endpoint = form.getAttribute("data-endpoint");
    var success = document.querySelector(".ct-success");
    var fail = form.querySelector(".ct-form__fail");
    var button = form.querySelector('button[type="submit"]');
    var label = form.querySelector("[data-ct-label]");
    var sending = false;

    var inputs = {
      ct_name: form.querySelector("#ct-name"),
      ct_email: form.querySelector("#ct-email"),
      ct_topic: form.querySelector("#ct-topic"),
      ct_message: form.querySelector("#ct-message"),
    };

    function setFieldError(key, on) {
      var field = form.querySelector('[data-field="' + key + '"]');
      if (!field) return;
      var error = field.querySelector(".ct-error");
      field.classList.toggle("ct-field--error", on);
      if (error) error.hidden = !on;
    }

    function clearErrors() {
      ["ct_name", "ct_email", "ct_message"].forEach(function (key) {
        setFieldError(key, false);
      });
      if (fail) fail.hidden = true;
    }

    // Same rules as the server (and the standalone design).
    function validate() {
      var bad = [];
      if (!inputs.ct_name.value.trim()) bad.push("ct_name");
      if (!/\S+@\S+\.\S+/.test(inputs.ct_email.value)) bad.push("ct_email");
      if (inputs.ct_message.value.trim().length <= 4) bad.push("ct_message");
      bad.forEach(function (key) {
        setFieldError(key, true);
      });
      if (bad.length) {
        var first = form.querySelector('[data-field="' + bad[0] + '"]');
        var input = first && first.querySelector("input, textarea");
        if (input) input.focus();
      }
      return !bad.length;
    }

    // Clear a field's error as soon as the visitor edits it.
    Object.keys(inputs).forEach(function (key) {
      if (!inputs[key]) return;
      inputs[key].addEventListener("input", function () {
        setFieldError(key, false);
      });
    });

    function setSending(on) {
      sending = on;
      if (button) {
        button.disabled = on;
        button.setAttribute("aria-busy", on ? "true" : "false");
      }
      if (label) label.textContent = on ? "Sending…" : "Send message";
    }

    function showFail(message) {
      if (!fail) return;
      fail.textContent =
        message ||
        "Something went wrong sending your message. Please try again in a " +
          "moment, or email support@ensurance.com directly.";
      fail.hidden = false;
    }

    function showSuccess() {
      var name = inputs.ct_name.value.trim();
      var first = success.querySelector("[data-ct-first]");
      var email = success.querySelector("[data-ct-email]");
      if (first) first.textContent = name.split(/\s+/)[0] || "there";
      if (email) email.textContent = inputs.ct_email.value.trim();
      form.hidden = true;
      success.hidden = false;
      success.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (sending) return;
      clearErrors();
      if (!validate()) return;

      var elapsed = form.querySelector('[name="ct_elapsed"]');
      if (elapsed) elapsed.value = String(Date.now() - loadedAt);

      setSending(true);
      fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          ct_name: inputs.ct_name.value,
          ct_email: inputs.ct_email.value,
          ct_topic: inputs.ct_topic.value,
          ct_message: inputs.ct_message.value,
          ct_company: form.querySelector("#ct-company").value,
          ct_elapsed: elapsed ? elapsed.value : "",
        }),
      })
        .then(function (response) {
          return response.json().then(function (json) {
            return { ok: response.ok, json: json };
          });
        })
        .then(function (result) {
          setSending(false);
          if (result.ok && result.json && result.json.ok) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
              event: "contact_form_submit",
              contact_topic: inputs.ct_topic.value || "general",
            });
            showSuccess();
            return;
          }
          // Server-side field errors (shouldn't normally happen — client
          // validation mirrors the server — but render them if they do).
          var data = result.json && result.json.data;
          if (data && data.fields) {
            Object.keys(data.fields).forEach(function (key) {
              setFieldError(key, true);
            });
          } else {
            showFail(result.json && result.json.message);
          }
        })
        .catch(function () {
          setSending(false);
          showFail();
        });
    });

    // "Send another message" — restore a fresh form.
    var reset = success && success.querySelector("[data-ct-reset]");
    if (reset) {
      reset.addEventListener("click", function () {
        form.reset();
        clearErrors();
        success.hidden = true;
        form.hidden = false;
        form.scrollIntoView({ behavior: "smooth", block: "center" });
        inputs.ct_name.focus();
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
