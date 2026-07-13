(function () {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function track(eventName, detail) {
    window.dispatchEvent(new CustomEvent('ranked:analytics', {
      detail: { event: eventName, ...(detail || {}) },
    }));
    if (Array.isArray(window.dataLayer)) {
      window.dataLayer.push({ event: eventName, ...(detail || {}) });
    }
  }

  document.querySelectorAll('[data-track]').forEach((element) => {
    element.addEventListener('click', () => track('audit_cta_click', {
      placement: element.dataset.track,
    }));
  });

  const auditForm = document.querySelector('#auditForm');
  document.querySelectorAll('a[href="#audit"]').forEach((link) => {
    link.addEventListener('click', () => track('audit_modal_open', {
      placement: link.dataset.track || 'page',
    }));
  });
  auditForm?.querySelector('[data-audit-next]')?.addEventListener('click', () => {
    if (auditForm.querySelector('[data-audit-step="1"]')?.checkValidity?.() !== false) {
      track('audit_step_complete', { step: 1 });
    }
  });
  auditForm?.addEventListener('submit', () => track('audit_submit_attempt'));

  const previewTabs = [...document.querySelectorAll('[data-audit-preview]')];
  const previewPanels = [...document.querySelectorAll('[data-audit-panel]')];
  function setPreview(name, focus) {
    previewTabs.forEach((tab) => {
      const active = tab.dataset.auditPreview === name;
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
      if (active && focus) tab.focus();
    });
    previewPanels.forEach((panel) => {
      const active = panel.dataset.auditPanel === name;
      panel.hidden = !active;
      panel.classList.toggle('is-active', active);
    });
    track('audit_preview_view', { panel: name });
  }
  previewTabs.forEach((tab, index) => {
    tab.addEventListener('click', () => setPreview(tab.dataset.auditPreview));
    tab.addEventListener('keydown', (event) => {
      if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
      event.preventDefault();
      const delta = event.key === 'ArrowRight' ? 1 : -1;
      const next = (index + delta + previewTabs.length) % previewTabs.length;
      setPreview(previewTabs[next].dataset.auditPreview, true);
    });
  });

  const topicLinks = [...document.querySelectorAll('.trade-faq__topics a')];
  topicLinks.forEach((link) => link.addEventListener('click', () => {
    topicLinks.forEach((item) => item.classList.toggle('is-active', item === link));
    track('faq_topic_click', { topic: link.textContent.trim() });
  }));
  document.querySelectorAll('.faq__question').forEach((button) => {
    button.addEventListener('click', () => track('faq_toggle', {
      question: button.querySelector('span')?.textContent.trim(),
    }));
  });

  const mobileStickyAudit = document.querySelector('.mobile-sticky-audit');
  const mobileBreakpoint = window.matchMedia('(max-width: 640px)');
  function updateMobileStickyAudit() {
    if (!mobileStickyAudit) return;
    mobileStickyAudit.classList.toggle('is-visible', mobileBreakpoint.matches && window.scrollY > 80);
  }
  window.addEventListener('scroll', updateMobileStickyAudit, { passive: true });
  mobileBreakpoint.addEventListener?.('change', updateMobileStickyAudit);
  updateMobileStickyAudit();

  const processSteps = [...document.querySelectorAll('.trade-process .process__step')];
  const progressDots = [...document.querySelectorAll('.trade-process__progress span')];
  if ('IntersectionObserver' in window && processSteps.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const index = processSteps.indexOf(entry.target);
        processSteps.forEach((step, stepIndex) => step.classList.toggle('is-current', stepIndex === index));
        progressDots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === index));
      });
    }, { rootMargin: '-35% 0px -45% 0px', threshold: 0 });
    processSteps.forEach((step) => observer.observe(step));
  }

  if (!reduce && window.gsap && window.ScrollTrigger) {
    gsap.from('.rank-panel__rows li', {
      opacity: 0,
      y: 14,
      duration: .45,
      stagger: .1,
      delay: .9,
      ease: 'power3.out',
    });
    gsap.utils.toArray('.trade-case__layout, .audit-screen, .trade-commitments__list li, .trade-fit__columns article')
      .forEach((element) => gsap.from(element, {
        scrollTrigger: { trigger: element, start: 'top 84%' },
        opacity: 0,
        y: 28,
        duration: .65,
        ease: 'power3.out',
      }));
  }
})();
