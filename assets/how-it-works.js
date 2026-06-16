/* ===================================================================
   "How Ensurance Works" — scroll-reveal transitions.

   Ported from the standalone design's App useEffect: every reveal
   target (sections marked .reveal, plus each timeline row .t-row)
   fades/slides in once its top crosses 88% of the viewport. The
   timeline number nodes scale up via a CSS transition tied to .is-in.

   home.js handles the rest of the chrome (mobile nav, FAQ tracking,
   sticky CTA); this file only owns the reveal motion. Respects
   prefers-reduced-motion by showing everything immediately.
   =================================================================== */
(function () {
  function init() {
    var targets = [].slice
      .call(document.querySelectorAll(".reveal"))
      .concat([].slice.call(document.querySelectorAll(".t-row")));

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

    // Seed once so anything already in view reveals on load.
    setTimeout(tick, 60);
    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
