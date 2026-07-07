/* ===================================================================
   "Health Insurance" (/health-insurance-quote-request) — scroll-reveal.

   Ported from the standalone design's App useEffect: every reveal
   target (sections marked .reveal) fades/slides in once its top crosses
   90% of the viewport.

   home.js handles the rest of the chrome (mobile nav, FAQ tracking,
   sticky CTA); this file only owns the reveal motion. Respects
   prefers-reduced-motion by showing everything immediately, and a
   safety timeout reveals anything still hidden so content can never
   get stuck behind the initial opacity:0.
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
