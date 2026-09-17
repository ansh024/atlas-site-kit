/* =========================================================
   Ranked International — case study page motion
   Loads after main.js (shares its $, $$, reduce, gsap, ScrollTrigger).
   Every init bails on missing elements so this file is safe on any page.
   ========================================================= */

/* ---------- hero: stat rows + chart card entrance ---------- */
function cspHero() {
  const card = $('.csp-chart-card');
  if (!card) return;

  if (reduce) return;

  const tl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: .5 });
  tl.from(card, { x: 40, opacity: 0, duration: 1 })
    .from('.csp-stat-row', { y: 16, opacity: 0, duration: .5, stagger: .1 }, '-=.7');

  const line = $('.csp-line', card);
  if (line) {
    const len = line.getTotalLength();
    gsap.set(line, { strokeDasharray: len, strokeDashoffset: len });
    gsap.to(line, { strokeDashoffset: 0, duration: 1.4, ease: 'power2.out', delay: .6 });
  }
  const dot = $('.csp-dot', card);
  if (dot) gsap.from(dot, { scale: 0, opacity: 0, duration: .4, ease: 'back.out(2.2)', delay: 1.9 });
}

/* ---------- count-up numbers (hero + metric grid) ---------- */
function cspCounters() {
  const nodes = $$('.csp-stat-row__num, .csp-chart-now strong, .csp-metric-num');
  if (!nodes.length) return;

  nodes.forEach(node => {
    const originalHTML = node.innerHTML;
    const raw = node.textContent.trim();
    const match = raw.match(/[\d,]+(\.\d+)?/);
    if (!match) return;
    const target = parseFloat(match[0].replace(/,/g, ''));
    if (Number.isNaN(target)) return;
    const prefix = raw.slice(0, match.index);
    const suffix = raw.slice(match.index + match[0].length);
    const decimals = (match[0].split('.')[1] || '').length;

    if (reduce) return; // leave static markup as-authored

    const counter = { val: 0 };
    gsap.set(node, { opacity: 1 });
    gsap.to(counter, {
      val: target,
      duration: 1.6,
      ease: 'power2.out',
      scrollTrigger: { trigger: node, start: 'top 85%', once: true },
      onUpdate: () => {
        const v = decimals ? counter.val.toFixed(decimals) : Math.round(counter.val).toLocaleString('en-US');
        node.textContent = prefix + v + suffix;
      },
      onComplete: () => { node.innerHTML = originalHTML; },
    });
  });
}

/* ---------- fade-up entrance: strategy / metric / related cards ---------- */
function cspReveal() {
  const groups = ['.csp-strategy__card', '.csp-metric', '.csp-related__card'];
  groups.forEach(sel => {
    const els = $$(sel);
    if (!els.length) return;
    if (reduce) { gsap.set(els, { opacity: 1, y: 0 }); return; }
    gsap.from(els, {
      scrollTrigger: { trigger: els[0].closest('.csp-strategy__grid, .csp-metric-grid, .csp-related__grid') || els[0], start: 'top 82%' },
      y: 26, opacity: 0, duration: .6, stagger: .1, ease: 'power3.out',
    });
  });

  const table = $('.csp-table-wrap');
  if (table) {
    if (reduce) { gsap.set(table, { opacity: 1, y: 0 }); }
    else gsap.from(table, { scrollTrigger: { trigger: table, start: 'top 85%' }, y: 24, opacity: 0, duration: .7, ease: 'power3.out' });
  }

  const lede = $('.csp-strategy__lede');
  if (lede) {
    if (reduce) { gsap.set(lede, { opacity: 1, y: 0 }); }
    else gsap.from(lede, { scrollTrigger: { trigger: lede, start: 'top 85%' }, y: 20, opacity: 0, duration: .6, ease: 'power3.out' });
  }
}

window.addEventListener('DOMContentLoaded', () => {
  cspHero(); cspCounters(); cspReveal();
  if (window.ScrollTrigger) ScrollTrigger.refresh();
});
