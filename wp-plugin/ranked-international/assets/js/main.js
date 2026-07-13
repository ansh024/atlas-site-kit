/* =========================================================
   Ranked International — main.js
   GSAP + ScrollTrigger. See docs/02-design-direction.md
   ========================================================= */
gsap.registerPlugin(ScrollTrigger);

const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const $  = (s, c = document) => c.querySelector(s);
const $$ = (s, c = document) => [...c.querySelectorAll(s)];

/* ---------- NAV: solidify on scroll ---------- */
const nav = $('#nav');
ScrollTrigger.create({
  start: 'top -80',
  onUpdate: self => nav.classList.toggle('is-stuck', self.scroll() > 80),
  onRefresh: self => nav.classList.toggle('is-stuck', self.scroll() > 80),
});

/* ---------- NAV: mobile hamburger ---------- */
const navBurger = $('#navBurger');
const navMobile = $('#navMenuMobile');
if (navBurger && navMobile) {
  navBurger.addEventListener('click', () => {
    const open = navBurger.getAttribute('aria-expanded') === 'true';
    navBurger.setAttribute('aria-expanded', String(!open));
    navMobile.classList.toggle('is-open', !open);
  });
  $$('a', navMobile).forEach(a => a.addEventListener('click', () => {
    navBurger.setAttribute('aria-expanded', 'false');
    navMobile.classList.remove('is-open');
  }));
}

/* ---------- HERO ---------- */
function hero() {
  if (reduce) return;
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
  // title + subhead animate together (rather than staggered) so the LCP
  // text paints sooner instead of waiting on a sequential reveal.
  tl.from('.hero__eyebrow', { y: 20, opacity: 0, duration: .6 })
    .from(['.hero__title', '.hero__sub'], { y: 28, opacity: 0, duration: .8, stagger: 0 }, '-=.2')
    .from('.hero__bottom .btn',        { y: 18, opacity: 0, duration: .5 }, '-=.3')
    .from('.hero__stat',   { y: 18, opacity: 0, duration: .5, stagger: .1 }, '-=.3');

  tl.from('.hero__visual', { x: 40, opacity: 0, duration: 1, ease: 'power3.out' }, '-=1.0');
}

/* ---------- HERO: play the supplied Google-SERP frames as a smooth eased climb
   f1/f2 = base · f3 = listing enters #3 · f4 = #2 · f5 = #1 winner.
   Crossfade + an upward "settle" drift so each step reads as motion, not a slide-show.
   ----------------------------------------------------------------------------- */
function frameClimb() {
  const stack = $('#framestack');
  if (!stack) return;
  const f = $$('.frame', stack);
  gsap.set(f, { opacity: 0, yPercent: 0 });
  gsap.set(f[0], { opacity: 1 });

  if (reduce) { gsap.set(f, { opacity: 0 }); gsap.set(f[4], { opacity: 1 }); return; }

  // fade from frame `a` to frame `b` after `hold`s; `drift` = upward settle (climb feel)
  const step = (tl, a, b, hold, drift = 2.4, dur = 0.55) => {
    tl.to(f[b], { opacity: 1, duration: dur, ease: 'power2.inOut' }, `+=${hold}`)
      .fromTo(f[b], { yPercent: drift }, { yPercent: 0, duration: dur + 0.25, ease: 'power3.out' }, '<')
      .to(f[a], { opacity: 0, duration: dur * 0.85, ease: 'power2.in' }, '<');
  };

  const tl = gsap.timeline({
    delay: 0.9, repeat: -1,
    onRepeat: () => { gsap.set(f, { opacity: 0, yPercent: 0 }); gsap.set(f[0], { opacity: 1 }); },
  });
  step(tl, 0, 1, 0.5, 0);      // base settle (no drift)
  step(tl, 1, 2, 0.7, 3);      // listing enters at #3
  step(tl, 2, 3, 0.85, 3);     // climbs to #2
  step(tl, 3, 4, 0.85, 3);     // climbs to #1 (winner card)
  tl.to({}, { duration: 2.4 }); // hold on #1
  step(tl, 4, 0, 0.0, 0, 0.6);  // ease back to base for a seamless loop
}

/* ---------- VIDEO REVIEWS: edit-bay swap ---------- */
function editBay() {
  const cards = $$('.vcard');
  if (!cards.length) return;
  const dots  = $$('.tdot');
  const playhead = $('.timeline__playhead');
  let active = 0, timer;

  function setActive(i) {
    active = i;
    cards.forEach((c, idx) => c.classList.toggle('is-active', idx === i));
    dots.forEach((d, idx) => d.classList.toggle('is-active', idx === i));
    // sweep the playhead across the active clip
    if (!reduce && playhead) {
      gsap.fromTo(playhead, { left: '0%' },
        { left: '100%', duration: 6, ease: 'none' });
    }
  }
  function next() { setActive((active + 1) % cards.length); }
  function start() { stop(); if (!reduce) timer = setInterval(next, 6000); }
  function stop()  { clearInterval(timer); }

  cards.forEach((c, i) => c.addEventListener('mouseenter', () => { setActive(i); start(); }));
  dots.forEach((d) => d.addEventListener('click', () => { setActive(+d.dataset.go); start(); }));

  // click play -> swap poster for real <video> if present in assets/video/
  $$('.vcard__play').forEach((btn, i) => btn.addEventListener('click', e => {
    e.stopPropagation();
    const screen = btn.closest('.vcard__screen');
    const assetBase = (window.RankdWP && window.RankdWP.assetsUrl) || 'assets/';
    const src = `${assetBase}video/review${i + 1}.mp4`;
    const v = document.createElement('video');
    v.src = src; v.controls = true; v.autoplay = true; v.playsInline = true;
    v.style.cssText = 'width:100%;height:100%;object-fit:cover';
    v.onerror = () => { btn.textContent = '▶'; }; // keep poster if no file yet
    screen.appendChild(v); btn.remove(); stop();
  }));

  setActive(0);
  // reveal stage on scroll-in
  gsap.from('.vcard', {
    scrollTrigger: { trigger: '.editbay', start: 'top 75%' },
    y: 40, opacity: 0, duration: .8, stagger: .12, ease: 'power3.out',
    onComplete: start,
  });
}

/* ---------- PRACTICE ACCORDION ---------- */
function practiceAccordion() {
  const practiceCards = Array.from(document.querySelectorAll('[data-practice-card]'));
  if (!practiceCards.length) return;

  function getVideo(card) { return card.querySelector('.practice-video'); }
  function getBtn(card) { return card.querySelector('.card-play-btn'); }

  function setCardState(card, playing) {
    const btn = getBtn(card);
    if (!btn) return;
    btn.dataset.state = playing ? 'playing' : 'paused';
    btn.setAttribute('aria-label', playing ? 'Pause video' : 'Play video');
  }

  function activateCard(card) {
    // Pause + deactivate all others
    practiceCards.forEach((c) => {
      if (c === card) return;
      c.classList.remove('is-active');
      const v = getVideo(c);
      if (v) v.pause();
      setCardState(c, false);
    });
    // Expand and play the chosen card
    card.classList.add('is-active');
    const video = getVideo(card);
    if (video && video.src) video.play().catch(() => {});
    setCardState(card, video && video.src ? true : false);
  }

  function toggleActiveCard(card) {
    const video = getVideo(card);
    if (!video || !video.src) return;
    if (video.paused) {
      video.play().catch(() => {});
      setCardState(card, true);
    } else {
      video.pause();
      setCardState(card, false);
    }
  }

  // Wire up play buttons
  practiceCards.forEach((card) => {
    const btn = getBtn(card);
    if (btn) {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (card.classList.contains('is-active')) {
          toggleActiveCard(card);
        } else {
          activateCard(card);
        }
      });
    }
  });

  // Auto-activate (and attempt autoplay) first card on init
  activateCard(practiceCards[0]);

  gsap.set(practiceCards, { clearProps: 'opacity' });
}

/* ---------- PROMISE: highlight-sweep ---------- */
function promise() {
  const marks = $$('#promiseText .hl');
  if (!marks.length) return;
  gsap.set(marks, {
    '--hl-progress': '20%',
    '--icon-opacity': 0,
    '--icon-y': '.16em',
    '--icon-scale': .86,
  });

  if (reduce) {
    gsap.set(marks, {
      '--hl-progress': '100%',
      '--icon-opacity': 1,
      '--icon-y': '0em',
      '--icon-scale': 1,
    });
    marks.forEach(m => m.classList.add('lit'));
    return;
  }

  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: '#promise',
      start: 'top 72%',
      end: 'bottom 42%',
      scrub: 0.8,
    },
  });

  marks.forEach((m, i) => {
    tl.to(m, {
      '--hl-progress': '100%',
      '--icon-opacity': 1,
      '--icon-y': '0em',
      '--icon-scale': 1,
      duration: 1,
      ease: 'none',
      onStart: () => m.classList.add('lit'),
    }, i * 0.28);
  });
}

/* ---------- SERVICES: constellation ---------- */
function constellation() {
  const wrap = $('#constellation');
  if (!wrap) return;
  const groups = [
    { label: 'SEO',        items: ['Local SEO','Organic SEO','Technical SEO','Enterprise SEO','Link Building'] },
    { label: 'Paid',       items: ['Google Ads','PPC','Enterprise PPC'] },
    { label: 'Consulting', items: ['SEO Consulting','CRO Audit'] },
  ];
  // layout: central node + nodes on a ring
  const W = 1000, H = 520, cx = W / 2, cy = H / 2;
  const all = groups.flatMap(g => g.items.map(t => ({ t, g: g.label })));
  const n = all.length;
  const svgNS = 'http://www.w3.org/2000/svg';
  const svg = document.createElementNS(svgNS, 'svg');
  svg.setAttribute('viewBox', `0 0 ${W} ${H}`);
  svg.classList.add('constellation__svg');
  svg.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;overflow:visible';

  const nodes = [];
  all.forEach((d, i) => {
    const ang = (i / n) * Math.PI * 2 - Math.PI / 2;
    const rx = 420, ry = 210;
    const x = cx + Math.cos(ang) * rx;
    const y = cy + Math.sin(ang) * ry;
    nodes.push({ ...d, x, y });
    const path = document.createElementNS(svgNS, 'path');
    path.setAttribute('d', `M${cx},${cy} C${cx},${y} ${x},${cy} ${x},${y}`);
    path.setAttribute('fill', 'none');
    path.setAttribute('stroke', 'rgba(202,255,0,.35)');
    path.setAttribute('stroke-width', '1.2');
    path.classList.add('cline');
    svg.appendChild(path);
  });
  wrap.appendChild(svg);

  // central node
  const center = document.createElement('div');
  center.className = 'cnode cnode--center';
  center.innerHTML = '<strong>Get you ranked</strong>';
  center.style.cssText = `left:50%;top:50%`;
  wrap.appendChild(center);

  // outer nodes (positioned in % of the 1000x520 box)
  nodes.forEach((nd, i) => {
    const el = document.createElement('div');
    el.className = 'cnode';
    el.dataset.group = nd.g;
    el.innerHTML = `<span class="cnode__g">${nd.g}</span>${nd.t}`;
    el.style.left = (nd.x / W * 100) + '%';
    el.style.top  = (nd.y / H * 100) + '%';
    wrap.appendChild(el);
  });

  // draw-on + pop-in
  const lines = $$('.cline', svg);
  lines.forEach(p => { const L = p.getTotalLength(); p.style.strokeDasharray = L; p.style.strokeDashoffset = L; });
  const tl = gsap.timeline({ scrollTrigger: { trigger: '.services', start: 'top 60%' } });
  tl.from(center, { scale: .6, opacity: 0, duration: .6, ease: 'back.out(1.6)' })
    .to(lines, { strokeDashoffset: 0, duration: .9, stagger: .04, ease: 'power2.out' }, '-=.2')
    .from('.cnode:not(.cnode--center)', { scale: .5, opacity: 0, duration: .5, stagger: .04, ease: 'back.out(1.7)' }, '-=.7');
}

/* ---------- SERVICES ROOF ---------- */
function servicesRoof() {
  const section = $('.services-roof');
  if (!section) return;
  const svg = $('.services-roof__lines', section);
  const paths = $$('.services-roof__lines path', section);
  const title = $('.services-roof h2', section);
  const center = $('.services-roof__center', section);
  const chips = $$('.services-roof__chip', section);
  const sparks = paths.map(path => {
    const spark = path.cloneNode();
    spark.classList.add('services-roof__spark');
    svg.appendChild(spark);
    return spark;
  });

  paths.forEach(path => {
    const length = path.getTotalLength();
    path.style.strokeDasharray = length;
    path.style.strokeDashoffset = length;
  });
  sparks.forEach(spark => {
    const length = spark.getTotalLength();
    spark.style.strokeDasharray = `18 ${length + 18}`;
    spark.style.strokeDashoffset = length + 18;
  });
  gsap.set(title, { y: 22, opacity: 0 });
  gsap.set(center, { scale: .86, opacity: 0 });
  gsap.set(chips, { y: 18, opacity: 0 });

  // mobile uses a stacked list (no hexagon map / connecting lines), and the
  // taller layout combined with scroll-jank from other pinned sections can
  // trip the scroll-triggered reveal's "reset" action, leaving chips stuck
  // at opacity 0. Skip the staggered reveal there and just show everything.
  const isMobile = window.matchMedia('(max-width: 860px)').matches;

  if (reduce || isMobile) {
    paths.forEach(path => { path.style.strokeDashoffset = 0; });
    sparks.forEach(spark => { spark.style.opacity = 0; });
    gsap.set([title, center, chips], { clearProps: 'all' });
    return;
  }

  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: section,
      start: 'top 55%',
      toggleActions: 'play none none reset',
    },
  })
    .to(title, { y: 0, opacity: 1, duration: .6, ease: 'power3.out' })
    .to(center, { scale: 1, opacity: 1, duration: .5, ease: 'back.out(1.7)' }, '-=.15');

  chips.forEach((chip, i) => {
    const path = paths[i];
    const spark = sparks[i];
    if (!path || !spark) return;

    tl.to(chip, { y: 0, opacity: 1, duration: .38, ease: 'power3.out' }, `+=${i === 0 ? .08 : .02}`)
      .to(path, { strokeDashoffset: 0, duration: .58, ease: 'power2.out' }, '>-0.04')
      .to(spark, { opacity: .95, duration: .01 }, '<')
      .to(spark, { strokeDashoffset: 0, duration: .58, ease: 'power2.out' }, '<')
      .to(spark, { opacity: 0, duration: .22, ease: 'power2.out' }, '>-0.14');
  });
}

/* ---------- CASE STUDIES ---------- */
function results() {
  const cards   = $$('[data-cs-idx]');
  const panels  = $$('[data-cs-metrics]');
  const prevBtn = $('[data-cs-prev]');
  const nextBtn = $('[data-cs-next]');
  const pips    = $$('.cs__pip');
  if (!cards.length) return;

  let current = 0;
  let autoTimer;

  function switchTo(toIdx, dir) {
    if (toIdx === current) return;

    // Kill ongoing tweens first, then immediately reset all state —
    // avoids zombie is-active classes when onComplete never fires
    cards.forEach(c  => { gsap.killTweensOf(c);  c.classList.remove('is-active'); gsap.set(c, { x: 0 }); });
    panels.forEach(p => { gsap.killTweensOf(p);  p.classList.remove('is-active'); gsap.set(p, { y: 0 }); });

    const fromIdx   = current;
    current         = toIdx;
    const fromCard  = cards[fromIdx];
    const toCard    = cards[toIdx];
    const fromPanel = panels[fromIdx];
    const toPanel   = panels[toIdx];

    pips.forEach((p, i) => p.classList.toggle('is-active', i === toIdx));

    // Outgoing card: read current opacity so mid-animation snaps look smooth
    const fromOp = +gsap.getProperty(fromCard, 'opacity') || 1;
    gsap.fromTo(fromCard,
      { opacity: fromOp, x: 0 },
      { opacity: 0, x: -dir * 30, duration: .32, ease: 'power2.in' }
    );

    // Incoming card slides in
    toCard.classList.add('is-active');
    gsap.fromTo(toCard,
      { opacity: 0, x: dir * 40 },
      { opacity: 1, x: 0, duration: .5, ease: 'power3.out', delay: .05 }
    );

    // Stagger inner elements of incoming card
    if (!reduce) {
      gsap.fromTo(toCard.querySelector('.cs__card-kicker'),
        { opacity: 0, y: 10 }, { opacity: 1, y: 0, duration: .38, ease: 'power3.out', delay: .2 });
      gsap.fromTo(toCard.querySelector('.cs__quote'),
        { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: .52, ease: 'power3.out', delay: .28 });
      gsap.fromTo(toCard.querySelector('.cs__card-foot'),
        { opacity: 0, y: 10 }, { opacity: 1, y: 0, duration: .42, ease: 'power3.out', delay: .38 });
    }

    // Metrics panel cross-fade
    const fromPanelOp = +gsap.getProperty(fromPanel, 'opacity') || 1;
    gsap.fromTo(fromPanel,
      { opacity: fromPanelOp, y: 0 },
      { opacity: 0, y: -10, duration: .26, ease: 'power2.in' }
    );
    toPanel.classList.add('is-active');
    gsap.fromTo(toPanel,
      { opacity: 0, y: 14 },
      { opacity: 1, y: 0, duration: .5, ease: 'power3.out', delay: .14 }
    );
  }

  function next() { switchTo((current + 1) % cards.length,  1); }
  function prev() { switchTo((current - 1 + cards.length) % cards.length, -1); }

  function startAuto() { stopAuto(); if (!reduce) autoTimer = setInterval(next, 5500); }
  function stopAuto()  { clearInterval(autoTimer); }

  if (prevBtn) prevBtn.addEventListener('click', () => { prev(); stopAuto(); startAuto(); });
  if (nextBtn) nextBtn.addEventListener('click', () => { next(); stopAuto(); startAuto(); });

  // Initial visible state
  cards[0].classList.add('is-active');
  panels[0].classList.add('is-active');
  pips[0]?.classList.add('is-active');
  gsap.set(cards[0],  { opacity: 1 });
  gsap.set(panels[0], { opacity: 1 });

  // ── Entrance animations ──────────────────────────────────
  if (reduce) { startAuto(); return; }

  const st = { trigger: '.case-studies', start: 'top 74%', once: true };

  gsap.from(['.cs__title', '.cs__see-all'], {
    scrollTrigger: st, y: 22, opacity: 0, duration: .65, stagger: .1, ease: 'power3.out',
  });

  ScrollTrigger.create({
    ...st,
    onEnter() {
      gsap.from('.cs__stage', { y: 36, opacity: 0, duration: .72, ease: 'power3.out', delay: .12 });
      const c0 = cards[0];
      gsap.fromTo(c0.querySelector('.cs__card-kicker'),
        { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: .42, ease: 'power3.out', delay: .44 });
      gsap.fromTo(c0.querySelector('.cs__quote'),
        { opacity: 0, y: 22 }, { opacity: 1, y: 0, duration: .58, ease: 'power3.out', delay: .54 });
      gsap.fromTo(c0.querySelector('.cs__card-foot'),
        { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: .48, ease: 'power3.out', delay: .66 });
    },
  });

  gsap.from('.cs__metrics-panel.is-active .cs__metric-block', {
    scrollTrigger: st,
    y: 28, opacity: 0, duration: .58, stagger: .15, ease: 'power3.out', delay: .22,
    onComplete: startAuto,
  });

  gsap.from('.cs__arrow', {
    scrollTrigger: st,
    scale: .7, opacity: 0, duration: .42, stagger: .1, ease: 'back.out(1.8)', delay: .4,
  });
  gsap.from('.cs__pip', {
    scrollTrigger: st,
    scale: 0, opacity: 0, duration: .36, stagger: .08, ease: 'back.out(2.2)', delay: .46,
  });
}

/* ---------- WHY list reveal ---------- */
function why() {
  gsap.from('.why__list li', {
    scrollTrigger: { trigger: '.why__list', start: 'top 75%' },
    y: 30, opacity: 0, duration: .6, stagger: .12, ease: 'power3.out',
  });
}

/* ---------- INDUSTRIES marquee ---------- */
function marquee() {
  const inds = ['Roofing','HVAC','Med Spa','Dentist','Plumbing','Law Firm','Auto Repair',
    'Landscaping','Electrician','Med Clinic','Real Estate','Restaurant','Chiropractor',
    'Pest Control','Garage Doors','Pool Service','Solar','Flooring','Veterinary'];
  const rows = $$('.marquee__row');
  rows.forEach(row => {
    // duplicate the set so a -50% / +50% shift is seamless
    const set = inds.map((t, i) =>
      `<span class="pill ${(i % 3 === 0) ? 'pill--out' : ''}">${t}</span>`).join('');
    row.innerHTML = set + set;
    if (reduce) return;
    const dir = +row.dataset.dir;          // 1 = leftward, -1 = rightward
    const w = row.scrollWidth / 2;
    gsap.set(row, { x: dir < 0 ? -w : 0 });
    gsap.to(row, { x: dir < 0 ? 0 : -w, duration: 38, ease: 'none', repeat: -1 });
    row.addEventListener('mouseenter', () => gsap.globalTimeline.timeScale(.2));
    row.addEventListener('mouseleave', () => gsap.globalTimeline.timeScale(1));
  });
}

/* ---------- generic section-title reveals ---------- */
function titles() {
  $$('.section-title').forEach(t => {
    gsap.from(t, {
      scrollTrigger: { trigger: t, start: 'top 82%' },
      y: 28, opacity: 0, duration: .7, ease: 'power3.out',
    });
  });
  $$('.process__steps li, .field, .cta__copy > *').forEach((el, i) => {
    gsap.from(el, {
      scrollTrigger: { trigger: el, start: 'top 88%' },
      y: 24, opacity: 0, duration: .55, ease: 'power3.out',
    });
  });
}

/* ---------- form ---------- */
function form() {
  const modal = $('#auditModal');
  const f = $('#auditForm');
  if (!modal || !f) return;

  const steps = {
    one: $('[data-audit-step="1"]', f),
    two: $('[data-audit-step="2"]', f),
    done: $('[data-audit-step="done"]', f),
  };
  let lastFocus = null;

  function showStep(step) {
    Object.values(steps).forEach(el => el?.classList.remove('is-active'));
    steps[step]?.classList.add('is-active');
  }

  function openAudit(e) {
    e?.preventDefault();
    lastFocus = document.activeElement;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('audit-modal-open');
    showStep('one');
    setTimeout(() => f.elements.name?.focus(), 40);
  }

  function closeAudit() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('audit-modal-open');
    f.reset();
    showStep('one');
    lastFocus?.focus?.();
  }

  $$('a[href="#audit"]').forEach(link => link.addEventListener('click', openAudit));
  $$('[data-audit-close]', modal).forEach(el => el.addEventListener('click', closeAudit));
  $('[data-audit-next]', f)?.addEventListener('click', () => {
    const fields = [f.elements.name, f.elements.email];
    if (fields.every(field => field.reportValidity())) {
      showStep('two');
      setTimeout(() => f.elements.website?.focus(), 40);
    }
  });
  $('[data-audit-back]', f)?.addEventListener('click', () => showStep('one'));
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeAudit();
  });

  f.addEventListener('submit', e => {
    e.preventDefault();
    const fields = [f.elements.website, f.elements.service];
    if (!fields.every(field => field.reportValidity())) return;

    if (window.RankdWP && window.RankdWP.ajaxUrl) {
      const body = new FormData();
      body.append('action', 'rankd_audit_lead');
      body.append('nonce', window.RankdWP.nonce);
      body.append('name', f.elements.name?.value || '');
      body.append('email', f.elements.email?.value || '');
      body.append('website', f.elements.website?.value || '');
      body.append('service', f.elements.service?.value || '');
      body.append('page_url', window.location.href);
      fetch(window.RankdWP.ajaxUrl, { method: 'POST', body })
        .finally(() => showStep('done'));
    } else {
      showStep('done');
    }
  });
}

/* ---------- FAQ ---------- */
function faq() {
  const items = $$('.faq__item');
  if (!items.length) return;

  items.forEach(item => {
    const button = $('.faq__question', item);
    if (!button) return;

    button.addEventListener('click', () => {
      const shouldOpen = !item.classList.contains('is-open');
      items.forEach(other => {
        other.classList.remove('is-open');
        $('.faq__question', other)?.setAttribute('aria-expanded', 'false');
      });
      item.classList.toggle('is-open', shouldOpen);
      button.setAttribute('aria-expanded', String(shouldOpen));
    });
  });
}

/* ---------- init ---------- */
window.addEventListener('DOMContentLoaded', () => {
  hero(); editBay(); practiceAccordion(); promise(); constellation(); servicesRoof(); results();
  why(); marquee(); titles(); faq(); form();
  ScrollTrigger.refresh();
});
