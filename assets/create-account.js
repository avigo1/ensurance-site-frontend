/* /create-account — Create Your Account interactions.
   Scoped to .ca-page. Vanilla JS, no jQuery. One job: the show/hide password
   toggle. One toggle reveals BOTH the password and confirm-password fields
   together (matches the design's shared show-state). data-targets is a
   space-separated list of input ids. */
(function () {
  "use strict";

  var page = document.querySelector(".ca-page");
  if (!page) return;

  page.querySelectorAll(".ca-pw-toggle").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var ids = (btn.getAttribute("data-targets") || "").split(/\s+/);
      var inputs = ids
        .map(function (id) { return document.getElementById(id); })
        .filter(Boolean);
      if (!inputs.length) return;

      var show = inputs[0].type === "password";
      inputs.forEach(function (input) {
        input.type = show ? "text" : "password";
      });
      btn.textContent = show ? "Hide" : "Show";
      btn.setAttribute("aria-pressed", show ? "true" : "false");
      btn.setAttribute("aria-label", show ? "Hide password" : "Show password");
    });
  });
})();
