/* Service template interactions: chapter rail, blueprint, FAQ, explanatory motion. */
(() => {
  const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const compactViewport = window.matchMedia('(max-width: 720px)').matches;
  if (window.lucide) window.lucide.createIcons();

  const chapters = $$('.chapter-rail__links a');
  const sections = chapters.map(link => document.querySelector(link.getAttribute('href'))).filter(Boolean);
  if (sections.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      const visible = entries.filter(entry => entry.isIntersecting).sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
      if (!visible) return;
      chapters.forEach(link => link.classList.toggle('is-active', link.getAttribute('href') === `#${visible.target.id}`));
    }, { rootMargin: '-20% 0px -65% 0px', threshold: [0, .15, .5] });
    sections.forEach(section => observer.observe(section));
  }

  const dataNode = document.querySelector('#workstreamData');
  const tabs = $$('.blueprint__tabs [role="tab"]');
  let workstreams = [];
  try { workstreams = JSON.parse(dataNode?.textContent || '[]'); } catch (error) { workstreams = []; }
  const panel = document.querySelector('#workstream-panel');
  const fields = {
    number: document.querySelector('#blueprintNumber'), title: document.querySelector('#blueprintTitle'),
    description: document.querySelector('#blueprintDescription'), deliverable: document.querySelector('#blueprintDeliverable'),
    outcome: document.querySelector('#blueprintOutcome'), icon: panel?.querySelector('.blueprint__icon')
  };
  function selectWorkstream(index, focus = false) {
    const item = workstreams[index];
    if (!item) return;
    tabs.forEach((tab, tabIndex) => { tab.setAttribute('aria-selected', String(tabIndex === index)); tab.tabIndex = tabIndex === index ? 0 : -1; });
    if (fields.number) fields.number.textContent = String(index + 1).padStart(2, '0');
    ['title', 'description', 'deliverable', 'outcome'].forEach(key => { if (fields[key]) fields[key].textContent = item[key] || ''; });
    if (fields.icon && window.lucide) { fields.icon.setAttribute('data-lucide', item.icon || 'check'); window.lucide.createIcons({ nodes: [fields.icon] }); }
    if (!reduceMotion && panel && window.gsap) gsap.fromTo(panel, { opacity: .65, y: 8 }, { opacity: 1, y: 0, duration: .35, ease: 'power2.out' });
    if (focus) tabs[index].focus();
  }
  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => selectWorkstream(index));
    tab.addEventListener('keydown', event => {
      if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) return;
      event.preventDefault();
      const next = event.key === 'Home' ? 0 : event.key === 'End' ? tabs.length - 1 : (index + (event.key === 'ArrowDown' ? 1 : -1) + tabs.length) % tabs.length;
      selectWorkstream(next, true);
    });
  });

  $$('.svc-faq__item button').forEach(button => button.addEventListener('click', () => {
    const item = button.closest('.svc-faq__item');
    const opening = !item.classList.contains('is-open');
    $$('.svc-faq__item').forEach(other => { other.classList.remove('is-open'); other.querySelector('button').setAttribute('aria-expanded', 'false'); });
    if (opening) { item.classList.add('is-open'); button.setAttribute('aria-expanded', 'true'); }
  }));

  if (!reduceMotion && window.gsap && window.ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);
    gsap.from('.search-console', { opacity: 0, x: 35, duration: .9, ease: 'power3.out', delay: .15 });
    gsap.from('.problem-row', { scrollTrigger: { trigger: '.problem-list', start: 'top 78%' }, opacity: 0, y: 24, stagger: .08, duration: .55, ease: 'power3.out' });
    if (!compactViewport) {
      const systemPaths = $$('.system-map path');
      systemPaths.forEach(path => { const length = path.getTotalLength(); gsap.set(path, { strokeDasharray: length, strokeDashoffset: length }); });
      gsap.timeline({ scrollTrigger: { trigger: '.system-map', start: 'top 72%' } })
        .to(systemPaths, { strokeDashoffset: 0, duration: .9, stagger: .08, ease: 'power2.out' })
        .from('.system-node', { opacity: 0, scale: .85, duration: .45, stagger: .06, ease: 'back.out(1.5)' }, '-=.65');
    }
  }
})();
