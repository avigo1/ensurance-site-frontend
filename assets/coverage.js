/* ===================================================================
   "Coverage Types" — tabbed picker + scroll-reveal transitions.

   Two independent behaviours:
     1. The coverage picker — a WAI-ARIA tablist. Clicking (or arrow-key
        navigating) a tab activates its matching detail panel. Falls back
        gracefully: with JS off, the first tab/panel is pre-marked active
        in the markup and the rest stay hidden.
     2. Scroll-reveal — sections marked .reveal fade/slide in once their
        top crosses 88% of the viewport (ported from how-it-works.js).

   home.js handles the rest of the chrome (mobile nav, FAQ tracking,
   sticky CTA). Respects prefers-reduced-motion.
   =================================================================== */
(function () {
  function initPicker() {
    var picker = document.querySelector(".cov-picker");
    if (!picker) return;

    var tabs = [].slice.call(picker.querySelectorAll('[role="tab"]'));
    if (!tabs.length) return;

    function panelFor(tab) {
      return document.getElementById(tab.getAttribute("aria-controls"));
    }

    function activate(tab, focus) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.classList.toggle("is-active", on);
        t.setAttribute("aria-selected", on ? "true" : "false");
        t.tabIndex = on ? 0 : -1;
        var panel = panelFor(t);
        if (panel) {
          panel.classList.toggle("is-active", on);
          if (on) {
            panel.removeAttribute("hidden");
          } else {
            panel.setAttribute("hidden", "");
          }
        }
      });
      if (focus) tab.focus();
    }

    tabs.forEach(function (tab, i) {
      tab.addEventListener("click", function () {
        activate(tab, false);
      });
      tab.addEventListener("keydown", function (e) {
        var next;
        if (e.key === "ArrowRight" || e.key === "ArrowDown") {
          next = tabs[(i + 1) % tabs.length];
        } else if (e.key === "ArrowLeft" || e.key === "ArrowUp") {
          next = tabs[(i - 1 + tabs.length) % tabs.length];
        } else if (e.key === "Home") {
          next = tabs[0];
        } else if (e.key === "End") {
          next = tabs[tabs.length - 1];
        } else {
          return;
        }
        e.preventDefault();
        activate(next, true);
      });
    });
  }

  function initReveal() {
    var targets = [].slice.call(document.querySelectorAll(".reveal"));
    if (!targets.length) return;

    var reduce =
      window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (reduce) {
      targets.forEach(function (el) {
        el.classList.add("is-in");
      });
      return;
    }

    var pending = targets.slice();

    function tick() {
      var trigger = window.innerHeight * 0.88;
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

    setTimeout(tick, 60);
    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);
  }

  function init() {
    initPicker();
    initReveal();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
