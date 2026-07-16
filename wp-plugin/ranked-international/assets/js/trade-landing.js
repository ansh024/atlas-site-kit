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

  const promiseSection = document.querySelector('.trade-promise');
  const promiseMarks = [...document.querySelectorAll('.trade-promise .hl')];
  let promiseFrame = 0;
  const updatePromise = () => {
    promiseFrame = 0;
    if (!promiseSection || !promiseMarks.length) return;
    const rect = promiseSection.getBoundingClientRect();
    const travel = Math.max(1, rect.height + window.innerHeight * .34);
    const overall = Math.max(0, Math.min(1, (window.innerHeight * .78 - rect.top) / travel));
    promiseMarks.forEach((mark, index) => {
      const local = Math.max(0, Math.min(1, overall * promiseMarks.length - index));
      mark.style.setProperty('--hl-progress', `${Math.round(local * 100)}%`);
    });
  };
  const requestPromiseUpdate = () => {
    if (promiseFrame) return;
    promiseFrame = requestAnimationFrame(updatePromise);
  };
  if (promiseMarks.length) {
    if (reduced) {
      promiseMarks.forEach((mark) => mark.style.setProperty('--hl-progress', '100%'));
    } else {
      promiseMarks.forEach((mark) => mark.style.setProperty('--hl-progress', '0%'));
      addEventListener('scroll', requestPromiseUpdate, { passive: true });
      addEventListener('resize', requestPromiseUpdate);
      updatePromise();
    }
  }

  const counters = [...document.querySelectorAll('[data-trade-count]')];
  const renderCounter = (element, value) => {
    const suffix = element.dataset.tradeSuffix || '';
    element.textContent = `${new Intl.NumberFormat('en-US').format(Math.round(value))}${suffix}`;
  };
  const animateCounter = (element) => {
    const target = Number(element.dataset.tradeCount || 0);
    if (reduced) {
      renderCounter(element, target);
      return;
    }
    const started = performance.now();
    const duration = 950;
    const tick = (now) => {
      const progress = Math.min(1, (now - started) / duration);
      const eased = 1 - Math.pow(1 - progress, 3);
      renderCounter(element, target * eased);
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };
  if (counters.length) {
    const counterObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      });
    }, { threshold: .45 });
    counters.forEach((counter) => {
      renderCounter(counter, 0);
      counterObserver.observe(counter);
    });
  }

  const revealTargets = [...document.querySelectorAll('.rank-panel__rows li, .trade-case__layout, .audit-deliverables, .trade-commitments__list li, .trade-fit__columns article, .trade-comparison__scroll')];
  if (!reduced && 'IntersectionObserver' in window && revealTargets.length) {
    revealTargets.forEach((element, index) => {
      element.classList.add('trade-reveal');
      if (element.matches('.rank-panel__rows li')) element.style.transitionDelay = `${index * 70}ms`;
    });
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-revealed');
        observer.unobserve(entry.target);
      });
    }, { threshold: .08, rootMargin: '0px 0px -8%' });
    revealTargets.forEach((element) => revealObserver.observe(element));
  }

}());
