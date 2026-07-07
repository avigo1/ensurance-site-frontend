/* ===================================================================
   "Trust Center" — sticky table-of-contents scroll-spy.

   Ported from the standalone design's TrustDoc useEffect: an
   IntersectionObserver watches each numbered section (plus the final
   #start CTA) and marks the matching TOC link `.is-active` as it scrolls
   into view. TOC clicks smooth-scroll to the section, offset for the
   sticky header.

   home.js handles the rest of the chrome (mobile nav, [data-track]
   analytics, FAQ tracking); this file only owns the TOC behaviour.
   =================================================================== */
(function () {
  function init() {
    var links = [].slice.call(document.querySelectorAll(".tc-toc__link"));
    if (!links.length) return;

    var byId = {};
    links.forEach(function (a) {
      byId[a.getAttribute("data-tc-toc")] = a;
    });

    var ids = Object.keys(byId);
    var sections = ids
      .map(function (id) {
        return document.getElementById(id);
      })
      .filter(Boolean);

    function setActive(id) {
      links.forEach(function (a) {
        a.classList.toggle("is-active", a.getAttribute("data-tc-toc") === id);
      });
    }

    // Smooth-scroll on TOC click, offset for the 68px sticky header.
    links.forEach(function (a) {
      a.addEventListener("click", function (e) {
        var id = a.getAttribute("data-tc-toc");
        var el = document.getElementById(id);
        if (!el) return;
        e.preventDefault();
        var top = el.getBoundingClientRect().top + window.scrollY - 84;
        window.scrollTo({ top: top, behavior: "smooth" });
        setActive(id);
      });
    });

    if (!("IntersectionObserver" in window)) return;

    var obs = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) setActive(en.target.id);
        });
      },
      { rootMargin: "-84px 0px -68% 0px", threshold: 0 }
    );
    sections.forEach(function (el) {
      obs.observe(el);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
