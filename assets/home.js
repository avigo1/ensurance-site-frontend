const navToggle = document.querySelector('.nav-toggle');
const primaryNav = document.querySelector('#primary-nav');

window.dataLayer = window.dataLayer || [];

if (navToggle && primaryNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = primaryNav.classList.toggle('is-open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });
}

function pushEvent(payload) {
  if (!payload || !payload.event) return;
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push(payload);
}

document.querySelectorAll('[data-track]').forEach((element) => {
  element.addEventListener('click', () => {
    pushEvent({
      event: element.getAttribute('data-track'),
      cta_text: element.getAttribute('data-cta-text') || element.innerText.trim(),
      page_type: element.getAttribute('data-page-type') || 'homepage',
      destination_url: element.getAttribute('href') || ''
    });
  });
});

document.querySelectorAll('.faq-list details').forEach((detail) => {
  detail.addEventListener('toggle', () => {
    if (!detail.open) return;
    const question = detail.querySelector('summary')?.textContent?.trim() || 'FAQ question';
    pushEvent({
      event: 'faq_expand',
      question,
      page_type: 'homepage'
    });
  });
});

const primaryHeroCta = document.querySelector('.hero-actions [data-track="cta_click_start_auto_quote_request"]');
if (primaryHeroCta && 'IntersectionObserver' in window) {
  let viewedPrimaryCta = false;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!viewedPrimaryCta && entry.isIntersecting) {
        viewedPrimaryCta = true;
        pushEvent({
          event: 'cta_view_shopper_primary',
          cta_text: 'Start My Auto Quote Request',
          page_type: 'homepage'
        });
        observer.disconnect();
      }
    });
  }, { threshold: 0.5 });
  observer.observe(primaryHeroCta);
}

let firedScroll50 = false;
let firedScroll90 = false;

function handleScrollTracking() {
  const scrollable = document.documentElement.scrollHeight - window.innerHeight;
  if (scrollable <= 0) return;
  const progress = (window.scrollY / scrollable) * 100;

  if (!firedScroll50 && progress >= 50) {
    firedScroll50 = true;
    pushEvent({ event: 'homepage_scroll_50', page_type: 'homepage' });
  }

  if (!firedScroll90 && progress >= 90) {
    firedScroll90 = true;
    pushEvent({ event: 'homepage_scroll_90', page_type: 'homepage' });
  }
}

const mobileStickyCta = document.querySelector('.mobile-sticky-cta');
function handleStickyCta() {
  if (!mobileStickyCta) return;
  if (window.innerWidth > 700) {
    mobileStickyCta.classList.remove('is-visible');
    return;
  }
  const footer = document.querySelector('.site-footer');
  const footerTop = footer ? footer.getBoundingClientRect().top : Infinity;
  const shouldShow = window.scrollY > 320 && footerTop > window.innerHeight;
  mobileStickyCta.classList.toggle('is-visible', shouldShow);
}

window.addEventListener('scroll', () => {
  handleScrollTracking();
  handleStickyCta();
}, { passive: true });

window.addEventListener('resize', handleStickyCta);
handleStickyCta();
