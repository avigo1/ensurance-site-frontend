/* /login — Agent Login interactions.
   Scoped to .al-page. Vanilla JS, no jQuery. Two jobs:
   1. Scroll-reveal for .reveal sections (progressive enhancement — CSS keeps
      content visible if JS never runs, because we add .is-in here).
   2. Show/hide password toggle on the login form. */
(function () {
  "use strict";

  var page = document.querySelector(".al-page");
  if (!page) return;

  /* ---- Password show/hide ---- */
  page.querySelectorAll(".al-pw-toggle").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var input = document.getElementById(btn.getAttribute("data-target"));
      if (!input) return;
      var show = input.type === "password";
      input.type = show ? "text" : "password";
      btn.textContent = show ? "Hide" : "Show";
      btn.setAttribute("aria-pressed", show ? "true" : "false");
      btn.setAttribute("aria-label", show ? "Hide password" : "Show password");
    });
  });

  /* ---- Scroll reveal ---- */
  var sections = Array.prototype.slice.call(page.querySelectorAll(".reveal"));
  if (!sections.length) return;

  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (reduce || !("IntersectionObserver" in window)) {
    sections.forEach(function (el) { el.classList.add("is-in"); });
    return;
  }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-in");
        io.unobserve(entry.target);
      }
    });
  }, { rootMargin: "0px 0px -8% 0px", threshold: 0.08 });

  sections.forEach(function (el) { io.observe(el); });

  /* Safety net: never leave content hidden. */
  setTimeout(function () { sections.forEach(function (el) { el.classList.add("is-in"); }); }, 1600);
})();
