(function () {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const track = (placement) => window.dispatchEvent(new CustomEvent('ranked:analytics', { detail: { event: 'audit_cta_click', placement } }));
  document.querySelectorAll('.trade-landing [data-track]').forEach((button) => button.addEventListener('click', () => track(button.dataset.track)));

  const sticky = document.querySelector('.mobile-sticky-audit');
  const mobile = window.matchMedia('(max-width: 640px)');
  const updateSticky = () => sticky?.classList.toggle('is-visible', mobile.matches && window.scrollY > 120);
  addEventListener('scroll', updateSticky, { passive: true });
  mobile.addEventListener?.('change', updateSticky);
  updateSticky();

  document.querySelectorAll('.trade-faq__topics a').forEach((link) => link.addEventListener('click', () => {
    document.querySelectorAll('.trade-faq__topics a').forEach((item) => item.classList.toggle('is-active', item === link));
  }));

  if (!reduced && window.gsap && window.ScrollTrigger) {
    gsap.from('.rank-panel__rows li', { opacity: 0, y: 14, duration: .45, stagger: .1, delay: .25, ease: 'power3.out' });
  }
}());
