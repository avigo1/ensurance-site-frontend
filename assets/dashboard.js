/* ===================================================================
   Agent Dashboard (/dashboard) — client-side view switching.

   The AgentDashboard design (templates/agent-dashboard/AgentDashboard.dc.html
   in the Ensurance Design System) is a stateful component: clicking a rail
   item calls setState({ view }) and the main region cross-fades in place
   (`.ens-view` / @keyframes ensViewFade). No navigation, no reload.

   This reproduces that WITHOUT giving up real URLs. Every view is rendered
   server-side into the DOM by page-dashboard.php; the rail items stay real
   <a href="?view=…"> links. This file intercepts the click, swaps which
   view carries .is-active, and rewrites the address bar with history
   .pushState — so the animation runs, but the URL, the back button, deep
   links and "open in new tab" all keep working.

   PROGRESSIVE ENHANCEMENT: if this file fails to load or JS is off, the
   links still navigate normally and PHP renders the requested view —
   exactly the behavior that shipped before this file existed. Nothing here
   is load-bearing for correctness, only for smoothness.

   The fade itself is pure CSS (see .dash-view in assets/dashboard.css): a
   hidden view has `display: none`, and showing it restarts the animation
   for free. This file never touches style or timing.
   =================================================================== */

(function () {
  'use strict';

  var shell = document.querySelector('.dashboard-shell');
  if (!shell) {
    return;
  }

  // The view a bare /dashboard/ (or an unknown ?view=) resolves to. Published
  // by page-dashboard.php from ensurance_dashboard_default_view(), so this
  // file cannot drift from the rail's first row the way a hardcoded slug did.
  var DEFAULT_VIEW = shell.getAttribute('data-default-view') || '';

  var items = Array.prototype.slice.call(shell.querySelectorAll('.dash-nav__item'));
  var views = Array.prototype.slice.call(shell.querySelectorAll('.dash-view'));

  // Nothing to switch between (e.g. a single-view page) — leave the links
  // as plain navigation rather than intercepting clicks for no benefit.
  if (items.length === 0 || views.length < 2) {
    return;
  }

  /**
   * The view slug a rail link points at. The href is the source of truth —
   * the same `?view=` arg ensurance_dashboard_current_view() reads server-
   * side — so the nav-item pattern needs no extra data attribute.
   */
  function viewFromHref(href) {
    try {
      return new URL(href, window.location.href).searchParams.get('view') || DEFAULT_VIEW;
    } catch (e) {
      return DEFAULT_VIEW;
    }
  }

  function viewFromLocation() {
    return viewFromHref(window.location.href);
  }

  function hasView(view) {
    return views.some(function (el) {
      return el.getAttribute('data-view') === view;
    });
  }

  /**
   * Show `view`, lighting its rail row. Mirrors exactly what
   * dashboard-nav-item.php and page-dashboard.php emit server-side, so the
   * DOM after a click is indistinguishable from a fresh page load.
   *
   * @param {string}  view      Slug to show.
   * @param {boolean} moveFocus Whether to pull focus to the new view. True
   *                            for real clicks (so keyboard and screen-reader
   *                            users land in the content they just asked
   *                            for), false on first paint and on back/
   *                            forward, where stealing focus is jarring.
   */
  function show(view, moveFocus) {
    // The view about to be hidden may be the one holding focus: a click puts
    // focus on its container (below), so a subsequent back/forward would hide
    // the element the user is standing on. `display: none` does not hand that
    // focus anywhere useful — it strands it on a hidden node, and the next Tab
    // restarts from the top of the document. Note it BEFORE the swap so the
    // focus can be carried over to the incoming view instead.
    //
    // This is deliberately narrower than `moveFocus`: it only RESCUES focus
    // that is already inside a view being hidden. Focus resting anywhere else
    // (the rail, the sign-out button, the page chrome) is left alone, which is
    // what keeps back/forward from stealing it.
    var focused = document.activeElement;
    var owner = focused && focused.closest ? focused.closest('.dash-view') : null;
    var rescueFocus = !!owner && owner.getAttribute('data-view') !== view;

    views.forEach(function (el) {
      el.classList.toggle('is-active', el.getAttribute('data-view') === view);
    });

    items.forEach(function (el) {
      var isActive = viewFromHref(el.href) === view;
      el.classList.toggle('is-active', isActive);
      if (isActive) {
        el.setAttribute('aria-current', 'page');
      } else {
        el.removeAttribute('aria-current');
      }
    });

    if (moveFocus || rescueFocus) {
      var active = shell.querySelector('.dash-view.is-active');
      if (active) {
        // Containers carry tabindex="-1" so they can receive focus without
        // entering the tab order. preventScroll keeps the rescue case silent —
        // back/forward should restore the page, not jump it.
        active.focus({ preventScroll: true });
      }
    }
  }

  shell.addEventListener('click', function (event) {
    var link = event.target.closest ? event.target.closest('.dash-nav__item') : null;
    if (!link || !shell.contains(link)) {
      return;
    }

    // Let the browser own anything that is not a plain left-click: new tab,
    // new window, download, "copy link". Intercepting these would break
    // expectations the real <a href> is there to satisfy.
    if (
      event.defaultPrevented ||
      event.button !== 0 ||
      event.metaKey ||
      event.ctrlKey ||
      event.shiftKey ||
      event.altKey
    ) {
      return;
    }

    var view = viewFromHref(link.href);

    // A rail item whose view has no container yet (later iterations add
    // both together) must still navigate, or the click would do nothing.
    if (!hasView(view)) {
      return;
    }

    event.preventDefault();

    // Re-clicking the current row is a no-op, but should not stack a
    // duplicate history entry.
    if (view !== viewFromLocation()) {
      window.history.pushState({ view: view }, '', link.href);
    }

    show(view, true);
  });

  // Back / forward through the views the user just clicked.
  window.addEventListener('popstate', function () {
    var view = viewFromLocation();
    show(hasView(view) ? view : DEFAULT_VIEW, false);
  });

  // Reconcile on first paint. PHP has already lit the right row, so this is
  // normally a no-op — it matters when the browser restores a bfcache page
  // or the URL carries a view with no container.
  var initial = viewFromLocation();
  show(hasView(initial) ? initial : DEFAULT_VIEW, false);
})();

/* ===================================================================
   Today's live request — one press per decision.

   Accept and Pass post a form, so the answer only arrives with the next
   page. Until it does the card looks exactly as it did before the press:
   nothing spins, nothing moves, and the two buttons sit 12px apart at
   48px tall. That silence is what produces the double press — and here
   the second press is not a duplicate but the OPPOSITE decision, filed
   because the first one appeared not to register.

   This closes both halves of that. The pressed button gets .is-loading
   (a spinner, see .dash-request__spinner in assets/dashboard.css) and the
   row gets .is-deciding, so the press is visible; and every submit after
   the first is refused, so a second press cannot file anything even if it
   happens in the same instant.

   Two things this deliberately does NOT do:

   - it never disables the buttons. A disabled submit button is dropped
     from the payload — taking `dash_decision` with it, i.e. the whole
     decision — and leaves focus on a node the browser no longer offers,
     so the next Tab restarts at the top of the page. Refusing the submit
     event does the same job with neither cost.
   - it never prevents the FIRST submit or touches what gets posted. The
     form is the same form; PHP still decides.

   The server is the real guard, not this file: the decision posts with a
   nonce and redirects (post-redirect-get), so a genuine double post
   cannot decide the same request twice, and with JS off the form works
   as it always has. What this adds is the part the server cannot — the
   card admitting, in the moment, that it heard you.
   =================================================================== */
(function () {
  'use strict';

  var form = document.querySelector('.dash-request__decide');
  if (!form) {
    return;
  }

  var buttons = Array.prototype.slice.call(form.querySelectorAll('button[type="submit"]'));
  var status = form.querySelector('.dash-request__status');
  if (buttons.length === 0) {
    return;
  }

  // What each decision says while it is in flight. Keyed by the value the
  // button posts, so a third decision added to ensurance_dashboard_decisions()
  // still spins — it just falls back to the generic line until named here.
  var PENDING_TEXT = {
    accept: 'Accepting this request…',
    pass: 'Passing this request…'
  };

  var deciding = false;

  // Which button was pressed. event.submitter is the right answer and is
  // everywhere current, but it is undefined on older Safari and null for a
  // form submitted programmatically, so the last pressed button is recorded
  // as the fallback. Neither is needed for correctness — the browser posts
  // the submitter's value regardless — only to know which button to spin.
  var lastPressed = null;

  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      lastPressed = button;
    });
  });

  form.addEventListener('submit', function (event) {
    if (deciding) {
      // The decision is already posting. This is the second press — the one
      // this file exists to swallow.
      event.preventDefault();
      return;
    }

    deciding = true;

    var pressed = event.submitter || lastPressed || buttons[0];

    form.classList.add('is-deciding');
    // For assistive tech the row is now busy; the buttons stay enabled and
    // stay in the tab order, exactly as they look.
    form.setAttribute('aria-busy', 'true');

    buttons.forEach(function (button) {
      button.classList.toggle('is-loading', button === pressed);
    });

    if (status) {
      // A screen reader gets no spinner, so it gets the sentence instead.
      // The region is empty until now, which is what keeps it silent on load.
      status.textContent = PENDING_TEXT[pressed.value] || 'Filing your decision…';
    }
  });

  // Back-button into a bfcache'd copy of this page restores the DOM as it
  // was — mid-press, spinner turning, submits refused — for a card that is
  // once again waiting on a decision. Put it back.
  window.addEventListener('pageshow', function (event) {
    if (!event.persisted) {
      return;
    }

    deciding = false;
    lastPressed = null;
    form.classList.remove('is-deciding');
    form.removeAttribute('aria-busy');

    buttons.forEach(function (button) {
      button.classList.remove('is-loading');
    });

    if (status) {
      status.textContent = '';
    }
  });
})();
