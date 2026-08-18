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

   THE RAIL IS NOT THE ONLY WAY IN. A view may link to another view from
   inside its own content — History's "Open in Today", on the row that is
   still awaiting a decision. Those links carry `.dash-view-link` and are
   intercepted exactly like a rail row, because they are the same act: same
   `?view=` href, same in-place swap, same pushState. What they are NOT is
   part of the rail — `items` below is still only `.dash-nav__item`, so a
   content link never takes the rail's highlight; show() lights whichever
   rail row points at the view that ends up on screen, which is the rail row
   for Today either way.

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

  // The RAIL's rows — the things that carry the highlight. Links inside a
  // view's content are intercepted too (see the note above) but are not in
  // this list, because nothing about them is ever lit.
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
   * Whether the copy of `view` in this document has been invalidated by
   * something the agent did on another view — see markStale() in the states
   * picker below, which is what sets the attribute.
   *
   * The rule this file follows either way is the same: what a view says is
   * PHP's answer, not this file's. Every view is rendered once, at load, so a
   * change made after that leaves one of them describing a record that no
   * longer exists — and the fix is to stop intercepting the click and let the
   * server render it again, not to patch the stale copy from here.
   */
  function isStale(view) {
    var stale = (shell.getAttribute('data-stale-views') || '').split(/\s+/).filter(Boolean);

    return stale.indexOf(view) !== -1;
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
    var link = event.target.closest
      ? event.target.closest('.dash-nav__item, .dash-view-link')
      : null;
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

    // …and neither is intercepted a view this session has invalidated. No
    // preventDefault, so the link the agent clicked navigates the ordinary way
    // and PHP re-renders it: the setup card disappears the first time they open
    // Today after setting a state, and comes back the first time they open it
    // after clearing them. One page load, on a click they were making anyway.
    if (isStale(view)) {
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

    // Same rule as the click: a stale view is not shown from the DOM. There is
    // no link to fall back on here, so the page reloads at the URL the history
    // entry already put in the address bar.
    if (isStale(view)) {
      window.location.reload();
      return;
    }

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

/* ===================================================================
   Requests — the filter pills and the sort toggle.

   BOTH ACT ON THE ROWS ALREADY IN THE DOM. Nothing here fetches, no URL
   changes, and ensurance_dashboard_request_rows() is neither re-queried
   nor re-ordered server-side: components/dashboard-view-requests.php
   renders the whole list, and this file only decides which of those rows
   are shown and in what order. The design's own filters are client-side
   for the same reason — the list is short and already here.

   PROGRESSIVE ENHANCEMENT, and stricter than the view switcher's: the
   controls ship with the `hidden` attribute and this file removes it. A
   pill is not a link and has nowhere to degrade to, so without JS the
   right answer is not a dead control but no control — an agent gets the
   full list, unfiltered, newest first, which is what they got before the
   controls existed.

   SORT IS A REVERSAL, NOT A RE-SORT. Rows arrive newest first as the
   caller's contract and may carry no timestamp at all (the queue can know
   an order it cannot date), so reversing the rendered order is the only
   transform that cannot drop an undated row to the bottom. It is also why
   the second order reads "Oldest first" rather than the design's "Renewal
   soonest": no request in this app carries a renewal date.
   =================================================================== */
(function () {
  'use strict';

  var controls = document.querySelector('.dash-requests-controls');
  var list = document.querySelector('.dash-requests');
  if (!controls || !list) {
    return;
  }

  var pills = Array.prototype.slice.call(controls.querySelectorAll('.dash-requests-pill'));
  var sortToggle = controls.querySelector('.dash-requests-controls__sort-toggle');
  var shownCount = document.querySelector('.dash-requests__note-shown');

  // The rows in the order PHP printed them — newest first. Held once, so the
  // reversal below has something to reverse BACK to.
  var rows = Array.prototype.slice.call(list.querySelectorAll('.dash-requests__row'));
  if (rows.length === 0) {
    return;
  }

  // What the toggle says in each order. Keyed by the value it carries, so the
  // button's label and its state can never disagree.
  var SORT_LABELS = {
    newest: 'Newest first',
    oldest: 'Oldest first'
  };

  var filter = 'all';

  /**
   * Apply the current filter: hide the rows in other states and report how
   * many are left. `hidden` rather than a class — it is the browser's own
   * "not rendered, not announced", so assistive tech and find-in-page agree
   * with what is on screen.
   */
  function applyFilter() {
    var shown = 0;

    rows.forEach(function (row) {
      var match = filter === 'all' || row.getAttribute('data-status') === filter;
      row.hidden = !match;
      if (match) {
        shown += 1;
      }
    });

    // Only the first number moves; the sentence around it is PHP's, so no
    // copy is assembled here.
    if (shownCount) {
      shownCount.textContent = String(shown);
    }
  }

  pills.forEach(function (pill) {
    pill.addEventListener('click', function () {
      filter = pill.getAttribute('data-filter') || 'all';

      pills.forEach(function (other) {
        var isActive = other === pill;
        other.classList.toggle('is-active', isActive);
        other.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });

      applyFilter();
    });
  });

  if (sortToggle) {
    sortToggle.addEventListener('click', function () {
      var next = sortToggle.getAttribute('data-sort') === 'newest' ? 'oldest' : 'newest';

      sortToggle.setAttribute('data-sort', next);
      sortToggle.textContent = SORT_LABELS[next];

      // Re-append in the wanted order. appendChild MOVES an existing node, so
      // this reorders the list in place rather than rebuilding it — the rows
      // keep their identity, and anything later steps attach to them survives.
      var ordered = next === 'oldest' ? rows.slice().reverse() : rows;
      ordered.forEach(function (row) {
        list.appendChild(row);
      });
    });
  }

  // Last: the controls only become visible once they work.
  controls.removeAttribute('hidden');
})();

/* ===================================================================
   Requests — the expanded detail panel.

   Step 2 of design_handoff_requests_page/README.md. Local UI state and
   nothing else: opening a row fetches nothing, records nothing and
   changes no URL. The panel is already in the document, rendered by
   components/dashboard-view-requests.php and shipped `hidden`.

   ONE OPEN AT A TIME, which is the prototype's behavior and the right
   one here — the panels are tall, and two open at once puts the second
   one's fields off the bottom of the screen with the row it belongs to
   scrolled away above them.

   WHY THE MARKUP CARRIES THE STATE. aria-expanded on the button and
   `hidden` on the panel are the state; this file only flips them. So a
   row is correctly closed before any JS runs, a row whose button never
   gets a listener is still closed rather than stuck open, and there is
   no second copy of "which row is open" to fall out of step with the DOM.

   Only rows with something behind them are <button>s (see the component),
   so the query below finds exactly the rows that open.
   =================================================================== */
(function () {
  'use strict';

  var list = document.querySelector('.dash-requests');
  if (!list) {
    return;
  }

  var lines = Array.prototype.slice.call(list.querySelectorAll('button.dash-requests__line'));
  if (lines.length === 0) {
    return;
  }

  /**
   * Open or close one row. Closing is unconditional on every other row, which
   * is what enforces one-at-a-time without tracking which one that is.
   *
   * @param {HTMLElement} line The row's toggle button.
   * @param {boolean}     open Whether it should end up open.
   */
  function setOpen(line, open) {
    line.setAttribute('aria-expanded', open ? 'true' : 'false');

    var panel = document.getElementById(line.getAttribute('aria-controls'));
    if (panel) {
      panel.hidden = !open;
    }
  }

  lines.forEach(function (line) {
    line.addEventListener('click', function () {
      var open = line.getAttribute('aria-expanded') !== 'true';

      lines.forEach(function (other) {
        setOpen(other, other === line && open);
      });
    });
  });
})();

/* ===================================================================
   Agency Profile — saving the agency name.

   Step 6 of the setup flow (design_handoff_agency_profile/SETUP-FLOW.md). The
   design has no Save button, so the field commits itself: on blur, and on
   Enter. Both do the same thing — submit the one-input form the field sits in
   — and PHP does the rest (ensurance_dashboard_handle_agency_name), so there
   is no endpoint here, no fetch, and no second copy of the save rules.

   WHAT IT REFUSES TO SEND, so the page does not reload for nothing:
   a value that is empty after trimming, and a value that has not changed. Both
   put the stored name back in the box, which is the revert the step asks for —
   done here without a round trip, and again on the server for the JS-off path.

   NO VALIDATION LIVES HERE. Trimming is not a rule about what a name may be,
   it is what "the agent typed a space" means. Length and characters are the
   app's business, and the app has nothing to say about them.

   Degrades to Enter: with JS off, a form with a single text input still submits
   on Enter, so the field still saves — only the blur commit is lost.
   =================================================================== */
(function () {
  'use strict';

  var form = document.querySelector('[data-agency-form]');

  if (!form) {
    return;
  }

  var input = form.querySelector('[data-agency-input]');

  if (!input) {
    return;
  }

  /* The value as the record holds it, read once at load: everything below is a
     comparison against it, and re-reading the field would just be comparing a
     value with itself. */
  var stored = input.value;
  var saving = false;

  function commit() {
    /* Submitting moves focus out of the field, which fires blur again. Without
       this the form would be submitted twice — once for the Enter, once for
       the blur behind it. */
    if (saving) {
      return;
    }

    var next = input.value.trim();

    if (next === '' || next === stored) {
      input.value = stored;
      return;
    }

    /* Post what was compared, not what was typed: otherwise a trailing space
       reaches the record and the next blur sees a change that is not one. */
    input.value = next;
    saving = true;
    form.submit();
  }

  input.addEventListener('blur', commit);

  input.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
      /* The browser would submit this form on Enter by itself, untrimmed and
         empty value included. Commit instead, on the same keystroke. */
      event.preventDefault();
      commit();
      return;
    }

    /* Escape abandons the edit. The revert is the whole of it — the blur that
       follows then finds nothing changed and sends nothing. */
    if (event.key === 'Escape') {
      input.value = stored;
      input.blur();
    }
  });
})();

/* ===================================================================
   Agency Profile — the served-states picker.

   The other interactive region on the dashboard's Agency Profile view — the
   agency-name field above is the first (components/dashboard-view-profile.php):
   add a state, remove a state, keep the count, the empty line and the hidden
   CSV field in step with the chips, and save each change.

   THE PAGE IS ALREADY A WORKING FORM before this file runs. "Add state" and
   every chip's × are submit buttons carrying the state they act on, so with no
   script at all the picker still works — one reload per change, through
   ensurance_dashboard_handle_states(). What this adds is that it stops doing
   that: the change is made in place and the same intent is posted with fetch,
   so an agent adding four states reads the same page throughout.

   IT POSTS AN INTENT, NOT THE LIST. "add California", never the whole CSV —
   two changes in quick succession therefore both land, and a page left open in
   another tab cannot write back a list that is no longer true. The hidden field
   is still kept current; it is simply not what saves.

   A CHANGE THAT DOES NOT LAND IS PUT BACK. On a refused or failed post the chip
   is restored (or re-removed) and the error line appears, so the list on screen
   is never a claim about the record that the record does not agree with.

   NO DUPLICATES BY CONSTRUCTION. An added state is removed from the select and
   a removed state is put back in alphabetical order, so the picker cannot
   offer a state the agent already has and there is no wrong choice to guard
   against after the fact — the same rule PHP applies on first render
   (ensurance_dashboard_state_choices) and again on save.

   LICENSING IS NOT THIS FILE'S BUSINESS. Nothing here blocks a state, checks
   one, or marks one verified — that is server-side truth, and the closing note
   on the view is what says so.
   =================================================================== */
(function () {
  'use strict';

  var root = document.querySelector('[data-states]');

  if (!root) {
    return;
  }

  var form = root.querySelector('[data-states-form]');
  var select = root.querySelector('[data-state-select]');
  var addBtn = root.querySelector('[data-state-add]');
  var list = root.querySelector('[data-states-list]');
  var empty = root.querySelector('[data-states-empty]');
  var count = root.querySelector('[data-state-count]');
  var value = root.querySelector('[data-states-value]');
  var error = root.querySelector('[data-states-error]');

  if (!form || !select || !addBtn || !list || !empty || !count || !value || !error) {
    return;
  }

  /* NO FETCH, NO INTERCEPTION. Everything below replaces a form that already
     works with something nicer; a browser that cannot make the request must be
     left with the working version rather than given a picker that changes the
     page and saves nothing. */
  if (!window.fetch || !window.URLSearchParams) {
    return;
  }

  var nonce = form.querySelector('[name="dash_states_nonce"]');

  /* Present only under `?slot=quiet`, where the chips are the sample agency's.
     A change to a sample is not a change to a record, so nothing is posted —
     the same conclusion ensurance_dashboard_handle_states() reaches from the
     same marker when the form posts the ordinary way. */
  var preview = form.querySelector('[data-states-preview]');

  function currentStates() {
    return Array.prototype.map.call(list.querySelectorAll('[data-state]'), function (chip) {
      return chip.getAttribute('data-state');
    });
  }

  /* The count, the empty line and the hidden field all describe the same list,
     so they are written in one place from one read of it — three separate
     updaters is how they end up disagreeing. */
  function sync() {
    var states = currentStates();
    var total = states.length;

    count.textContent = total === 0 ? 'none set' : (total === 1 ? '1 state' : total + ' states');

    if (total === 0) {
      list.setAttribute('hidden', '');
      empty.removeAttribute('hidden');
    } else {
      list.removeAttribute('hidden');
      empty.setAttribute('hidden', '');
    }

    value.value = states.join(',');
  }

  function chipFor(name) {
    return Array.prototype.filter.call(list.querySelectorAll('[data-state]'), function (chip) {
      return chip.getAttribute('data-state') === name;
    })[0] || null;
  }

  function optionFor(name) {
    return Array.prototype.filter.call(select.options, function (option) {
      return option.value === name;
    })[0] || null;
  }

  /* Put an option back where it belongs rather than on the end: the list is
     alphabetical, and a state that was removed and re-offered at the bottom
     would be the one state nobody can find. */
  function restoreOption(name, code) {
    var option = document.createElement('option');
    var before = null;
    var options = select.querySelectorAll('option[data-code]');

    option.value = name;
    option.textContent = name;
    option.setAttribute('data-code', code || '');

    Array.prototype.some.call(options, function (existing) {
      if (existing.textContent.localeCompare(name) > 0) {
        before = existing;
        return true;
      }
      return false;
    });

    select.insertBefore(option, before);
  }

  /* ── The two DOM primitives. Neither posts: they are called both by the
     agent's own action and by the undo that follows a failed save, and an undo
     that posted would be a second change to report. ── */

  function addChip(name, code) {
    var option = optionFor(name);
    var chip = document.createElement('li');

    chip.className = 'dash-profile__chip';
    chip.setAttribute('data-state', name);
    chip.innerHTML =
      (code ? '<span class="dash-profile__chip-code"></span>' : '') +
      '<span class="dash-profile__chip-name"></span>' +
      '<button type="submit" class="dash-profile__chip-remove" name="dash_state_remove" data-state-remove>' +
      '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>' +
      '</button>';

    // textContent and .value rather than interpolation: the names come from our
    // own select, but building markup out of values is how that stops being
    // true the first time this list comes from storage.
    if (code) {
      chip.querySelector('.dash-profile__chip-code').textContent = code;
    }
    chip.querySelector('.dash-profile__chip-name').textContent = name;

    // The same submit value the server-rendered chips carry, so a chip added
    // here removes itself the no-script way too if the script later fails.
    chip.querySelector('[data-state-remove]').value = name;
    chip.querySelector('[data-state-remove]').setAttribute('aria-label', 'Remove ' + name);

    list.appendChild(chip);

    if (option) {
      option.parentNode.removeChild(option);
    }

    sync();
  }

  function removeChip(chip) {
    var codeEl = chip.querySelector('.dash-profile__chip-code');

    chip.parentNode.removeChild(chip);
    restoreOption(chip.getAttribute('data-state'), codeEl ? codeEl.textContent : '');
    sync();
  }

  /* ── Saving ── */

  function showError() {
    error.removeAttribute('hidden');
  }

  function hideError() {
    error.setAttribute('hidden', '');
  }

  /* A SAVED CHANGE DATES THE REST OF THE PAGE. Every view is in this document
     already, rendered when it loaded, so Today is still showing the slot the
     old list produced — the setup card an agent has just finished with, or the
     quiet card they have just emptied out from under.

     This does not fix that by re-rendering anything: it marks the views that
     are a rendering of the served states, and the view switcher stops
     intercepting the links to them (see isStale above), so the server draws
     them again on the next click. What the slot should now say stays in PHP,
     where the four states and their copy already live.

     WHICH views is read from the markup, not decided here:
     components/dashboard-view-today.php puts data-depends-on-states on the
     priority slot. Anything else that comes to depend on the list says so the
     same way and needs no change to this file. */
  function markStale() {
    var shell = document.querySelector('.dashboard-shell');
    var dependents = document.querySelectorAll('[data-depends-on-states]');
    var stale;

    if (!shell) {
      return;
    }

    stale = (shell.getAttribute('data-stale-views') || '').split(/\s+/).filter(Boolean);

    Array.prototype.forEach.call(dependents, function (dependent) {
      var view = dependent.closest ? dependent.closest('.dash-view') : null;
      var slug = view ? view.getAttribute('data-view') : '';

      if (slug && stale.indexOf(slug) === -1) {
        stale.push(slug);
      }
    });

    shell.setAttribute('data-stale-views', stale.join(' '));
  }

  /* One request per change, carrying the state that changed and the form's own
     nonce. `undo` puts the list back if it does not land — it is passed rather
     than derived, because by the time the answer arrives the agent may have
     changed something else. */
  function post(field, name, undo) {
    var body;

    if (preview) {
      return;
    }

    body = new window.URLSearchParams();
    body.set(field, name);
    body.set('dash_states_nonce', nonce ? nonce.value : '');
    body.set('dash_states_async', '1');

    function failed() {
      undo();
      showError();
    }

    window.fetch(form.action, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: body.toString()
    }).then(function (response) {
      if (response.ok) {
        hideError();
        markStale();
        return;
      }

      failed();
    }).catch(failed);
  }

  /* ── The two things an agent can do ── */

  function addState() {
    var name = select.value;
    var option = select.options[select.selectedIndex];
    var code = option ? option.getAttribute('data-code') : '';

    // A no-op when nothing is chosen, and — belt and braces, since the option
    // is removed on add — when the state is somehow already listed. Nothing is
    // posted and nothing is said: there was no wrong choice to report.
    if (!name || currentStates().indexOf(name) !== -1) {
      return;
    }

    addChip(name, code);

    // Back to the placeholder, so the control reads as ready for the next one
    // rather than still showing what was just added.
    select.value = '';

    post('dash_state_add', name, function () {
      var chip = chipFor(name);

      if (chip) {
        removeChip(chip);
      }
    });
  }

  function removeState(chip) {
    var codeEl = chip.querySelector('.dash-profile__chip-code');
    var name = chip.getAttribute('data-state');
    var code = codeEl ? codeEl.textContent : '';

    removeChip(chip);

    post('dash_state_remove', name, function () {
      addChip(name, code);
    });
  }

  /* preventDefault on the click of a submit button is what stops the form
     submitting — the post below replaces the navigation, it does not follow
     it. */
  addBtn.addEventListener('click', function (event) {
    event.preventDefault();
    addState();
  });

  // Delegated, so chips added after load carry the behavior without being
  // wired individually.
  list.addEventListener('click', function (event) {
    var button = event.target.closest ? event.target.closest('[data-state-remove]') : null;

    if (button) {
      event.preventDefault();
      removeState(button.closest('[data-state]'));
    }
  });

  /* Enter inside the select submits the form on its own, without going through
     either handler above. The browser would use the first submit button — "Add
     state" — so that is exactly what this does, minus the navigation. */
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    addState();
  });

  /* ARRIVING WITH NOTHING SET, the picker is the only thing on the view worth
     doing, so it takes focus. Only when the view is already the active one and
     the list is empty: a profile that already has states is being read, not
     filled in, and moving focus into a control the agent did not ask for
     scrolls the page out from under them. */
  if (!currentStates().length) {
    var view = root.closest ? root.closest('.dash-view') : null;

    if (view && view.classList.contains('is-active')) {
      select.focus();
    }
  }
})();

/* ===================================================================
   History — "Copy details" on a purchased lead.

   The third action in the panel's contact strip, beside the tel: and
   mailto: links. Those two are real links and need nothing from this
   file; the clipboard has no markup equivalent, so this is the one
   contact action that is script-only — and it is therefore the one that
   ships `hidden` and is revealed here.

   REVEALED ONLY WHERE IT WORKS. navigator.clipboard is absent on
   insecure origins and in older browsers, and a button that looks
   pressable and silently copies nothing is worse than no button: the
   agent pastes into their agency system and gets whatever was on the
   clipboard before. So the check comes first and the reveal comes after.

   THE TEXT IS THE SERVER'S. components/dashboard-view-requests.php
   assembles the record into data-lead-copy at render time — this file
   never reads the panel's DOM for it, so the paste cannot drift when the
   layout does.
   =================================================================== */
(function () {
  'use strict';

  var list = document.querySelector('.dash-requests');
  if (!list) {
    return;
  }

  var buttons = Array.prototype.slice.call(list.querySelectorAll('[data-lead-copy]'));
  if (buttons.length === 0) {
    return;
  }

  // No clipboard, no button — see above. Every copy button on the view stays
  // hidden, which is the state PHP already rendered.
  if (!window.navigator || !window.navigator.clipboard || !window.navigator.clipboard.writeText) {
    return;
  }

  // How long the confirmation stays up. Long enough to read, short enough that
  // it is gone before the agent looks back at the row.
  var SAID_MS = 2400;

  buttons.forEach(function (button) {
    var strip = button.parentNode;
    var said = strip ? strip.querySelector('[data-lead-copied]') : null;
    var timer = 0;

    /* The line is a live region (role="status"), so writing to it is what
       announces the copy — there is no other evidence on the page that
       anything happened. */
    function report(message) {
      if (!said) {
        return;
      }

      said.textContent = message;

      window.clearTimeout(timer);
      timer = window.setTimeout(function () {
        said.textContent = '';
      }, SAID_MS);
    }

    button.addEventListener('click', function () {
      var text = button.getAttribute('data-lead-copy') || '';

      window.navigator.clipboard.writeText(text).then(function () {
        report('Copied');
      }).catch(function () {
        /* A permission prompt the agent dismissed, or a document that lost
           focus mid-write. Nothing is retried and nothing is faked — the row
           says the copy did not happen and the details are still on screen to
           select by hand. */
        report('Could not copy');
      });
    });

    button.removeAttribute('hidden');
  });
})();

/* ===================================================================
   History — the status and private note on a purchased lead.

   THE PAGE IS ALREADY A WORKING FORM before this file runs, the same way
   the states picker is: Save note is a real submit, the form posts to the
   History view, ensurance_dashboard_handle_lead_note() writes the entry
   and the redirect comes back with that row reopened. With no script at
   all an agent can file every note they want, at one reload each.

   WHAT THIS ADDS is that the reload stops happening: the same body is
   posted with fetch, so the panel the agent is reading stays open and the
   record beside the note stays on screen while they write it.

   IT POSTS THE WHOLE ENTRY, unlike the states picker's intent. Status and
   note are one thought filed together — an agent marks "Quoted" and says
   what they quoted in the same breath — and the handler replaces the
   entry whole, which is what makes clearing a status expressible at all.

   NOTHING IS OPTIMISTIC. The line under the button says "Saved" only
   after the server said so; a post that does not land says so and leaves
   the typed text exactly where it is, because the box is then the only
   copy of it. The stamp itself is deliberately vague ("Saved just now")
   rather than a clock reading — the record's own `at` is the truth, and
   this file does not have it until the next render.
   =================================================================== */
(function () {
  'use strict';

  var forms = Array.prototype.slice.call(document.querySelectorAll('[data-lead-form]'));
  if (forms.length === 0) {
    return;
  }

  // No fetch, no interception — the form keeps its native submit, which works.
  if (!window.fetch || !window.URLSearchParams) {
    return;
  }

  forms.forEach(function (form) {
    var ref = form.querySelector('[name="dash_lead_ref"]');
    var nonce = form.querySelector('[name="dash_lead_nonce"]');
    var status = form.querySelector('[name="dash_lead_status"]');
    var note = form.querySelector('[name="dash_lead_note"]');
    var save = form.querySelector('.dash-requests__log-save');
    var said = form.querySelector('[data-lead-said]');
    var error = form.querySelector('[data-lead-error]');

    // A form missing any of its own parts is left to submit natively rather
    // than posted half-built.
    if (!ref || !nonce || !status || !note || !save) {
      return;
    }

    var saving = false;

    function show(el, visible) {
      if (el) {
        el.hidden = !visible;
      }
    }

    function post() {
      if (saving) {
        return;
      }

      var body = new window.URLSearchParams();
      body.set('dash_lead_ref', ref.value);
      body.set('dash_lead_status', status.value);
      body.set('dash_lead_note', note.value);
      body.set('dash_lead_nonce', nonce.value);
      body.set('dash_lead_async', '1');

      saving = true;
      save.disabled = true;

      function failed() {
        show(said, false);
        show(error, true);
      }

      window.fetch(form.action, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: body.toString()
      }).then(function (response) {
        if (response.ok) {
          show(error, false);

          if (said) {
            said.textContent = 'Saved just now';
            show(said, true);
          }

          return;
        }

        failed();
      }).catch(failed).then(function () {
        saving = false;
        save.disabled = false;
      });
    }

    /* preventDefault is what stops the navigation — the post replaces it. This
       catches the button, Enter in the select, and anything else the browser
       counts as a submit. */
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      post();
    });

    /* A note that has been edited since the last save has an unsaved copy in
       the box and nowhere else, so the stamp under it would be describing an
       older entry. Clearing it is the honest state: the agent has typed
       something and not saved it yet. */
    function stale() {
      show(said, false);
      show(error, false);
    }

    note.addEventListener('input', stale);
    status.addEventListener('change', stale);
  });
})();
