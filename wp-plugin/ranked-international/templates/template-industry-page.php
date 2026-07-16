<?php
/**
 * Single template for the `rip_industry` post type.
 * All content comes from the "Industry Page Details" ACF field group
 * (see acf-json/group_rip_industry_page.json) — duplicate an Industry Page
 * post and fill in the fields to publish a new one (e.g. HVAC, plumbing),
 * no code required.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$industry_name = get_field( 'industry_name' ) ?: get_the_title();
$hero_video_poster = get_field( 'hero_video_poster' );
$hero_video     = get_field( 'hero_video' );
$hub_url        = rip_url_for_template( 'templates/template-case-studies-hub.php', '/case-studies/' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet"></noscript>

<style>
  /* Industry-page-only overrides, kept in sync with construction/index.html. */
  .hero--industry { height: 660px; }
  .hero--industry .hero__inner { padding-right: 110px; }
  .hero--industry .hero__copy { padding-top: 80px; width: auto; max-width: 620px; }
  .hero--industry .hero__title { font-size: clamp(2.6rem, 6vw, 3.5rem); max-width: 17ch; }
  .hero--industry .hero__sub { max-width: 44ch; }
  .hero--industry .hero__visual { width: 420px; justify-content: center; padding-top: 30px; }
  @media (max-width: 1180px) and (min-width: 641px) {
    .hero--industry { height: auto; }
    .hero--industry .hero__inner { flex-direction: column; align-items: center; text-align: center; padding: 110px 40px 60px; gap: 48px; }
    .hero--industry .hero__copy { max-width: 620px; padding-top: 0; }
    .hero--industry .hero__title, .hero--industry .hero__sub { margin-left: auto; margin-right: auto; }
    .hero--industry .hero__bottom { justify-content: center; flex-wrap: wrap; gap: 24px; }
    .hero--industry .hero__visual { width: 100%; max-width: 420px; margin: 0 auto; padding-top: 0; }
  }
  @media (max-width: 640px) {
    .hero--industry { height: auto; }
    .hero--industry .hero__inner { padding-right: 0; }
    .hero--industry .hero__visual { width: 100%; margin: 0; }
    .hero--industry .hero__title { font-size: 26px; }
  }

  .proof { padding: clamp(70px,9vw,110px) var(--pad); background: var(--white); }
  .proof__inner { max-width: var(--maxw); margin: 0 auto; }
  .proof__grid { margin-top: 48px; display: grid; grid-template-columns: repeat(3,1fr); }
  .proof__card { padding: 0 32px; border-left: 1px solid var(--line); }
  .proof__card:first-child { border-left: none; padding-left: 0; }
  .proof__num { font-family: var(--font-display); font-weight: 800; font-size: clamp(2.6rem,5vw,3.8rem); letter-spacing: -.03em; line-height: 1; color: var(--prussian-blue); }
  .proof__num span { color: var(--ocean-twilight); }
  .proof__label { margin-top: .6em; font-size: .98rem; color: var(--ink-soft); line-height: 1.5; max-width: 26ch; }
  @media (max-width: 860px) { .proof__grid { grid-template-columns: 1fr; gap: 32px; } .proof__card { border-left: none; padding-left: 0; border-top: 1px solid var(--line); padding-top: 28px; } .proof__card:first-child { border-top: none; padding-top: 0; } }

  .segments { padding: clamp(70px,9vw,110px) var(--pad); background: var(--bright-snow); }
  .segments__inner { max-width: var(--maxw); margin: 0 auto; }
  .segments__banner { margin-top: 40px; border-radius: 20px; overflow: hidden; box-shadow: 0 24px 50px -24px rgba(10,21,41,.35); }
  .segments__banner img { width: 100%; height: clamp(160px,22vw,300px); object-fit: cover; object-position: center 30%; display: block; }
  .segments__grid { margin-top: 32px; display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; }
  .segment-card { background: var(--white); border: 1px solid var(--line); border-radius: var(--r); padding: 26px 24px; box-shadow: 0 10px 30px -20px rgba(10,21,41,.4); transition: transform .3s var(--ease), box-shadow .3s var(--ease); }
  .segment-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -16px rgba(10,21,41,.22); }
  .segment-card__icon { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 10px; background: var(--prussian-blue); color: var(--chartreuse); margin-bottom: 16px; }
  .segment-card__icon i { width: 20px; height: 20px; display: flex; }
  .segment-card h3 { font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; color: var(--prussian-blue); margin-bottom: 8px; line-height: 1.2; }
  .segment-card p { font-size: .9rem; color: var(--ink-soft); line-height: 1.5; margin-bottom: 12px; }
  .segment-card__kw { font-size: .74rem; letter-spacing: .02em; color: var(--ocean-twilight); font-weight: 600; }
  .segment-card__kw span { color: var(--ink-soft); font-weight: 400; }
  @media (max-width: 980px) { .segments__grid { grid-template-columns: repeat(2,1fr); } }
  @media (max-width: 560px) { .segments__grid { grid-template-columns: 1fr; } }

  .compare { padding: clamp(70px,9vw,110px) var(--pad) clamp(110px,12vw,150px); background: var(--white); }
  .compare__inner { max-width: 1080px; margin: 0 auto; }
  .compare__head { text-align: center; max-width: 900px; margin: 0 auto; }
  .compare__head .section-title { margin: 0 auto; max-width: 700px; text-align: center; }
  .compare__subhead { font-family: var(--font-serif); font-style: italic; font-weight: 400; font-size: clamp(1.15rem,2.4vw,1.5rem); color: var(--ocean-twilight); text-align: center; margin-top: 10px; }
  .cmp-table { position: relative; margin: 52px auto 0; max-width: 640px; border: 1.5px solid var(--prussian-blue); border-radius: 26px; background: var(--white); padding-bottom: 28px; }
  .cmp-table__body { position: relative; padding: 30px 22px 0; }
  .cmp-table__row { position: relative; z-index: 1; display: flex; align-items: stretch; }
  .cmp-table__row:not(:last-child) { border-bottom: 1px solid var(--line); }
  .cmp-table__row--head { border-bottom: none !important; padding-bottom: 18px; }
  .cmp-table__cell { flex: 1; text-align: center; padding: 16px 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
  .cmp-table__cell--label { flex: none; width: 168px; text-align: left; align-items: flex-start; justify-content: center; padding-left: 4px; padding-right: 10px; font-size: .88rem; line-height: 1.35; color: var(--ink); }
  .cmp-table__cell--label span { display: block; color: var(--ink-soft); font-size: .82rem; margin-top: 2px; }
  .cmp-table__cell--hi { background: rgba(16,70,186,.12); margin: 0 -6px; }
  .cmp-table__row--head .cmp-table__cell--hi { border-radius: 16px 16px 0 0; }
  .cmp-table__row--last .cmp-table__cell--hi { border-radius: 0 0 16px 16px; }
  .cmp-table__col-head { font-family: var(--font-display); font-weight: 700; font-size: 1rem; line-height: 1.2; color: var(--prussian-blue); }
  .cmp-table__num { font-family: var(--font-display); font-weight: 800; font-size: clamp(1.3rem,3vw,1.7rem); color: var(--prussian-blue); letter-spacing: -.02em; }
  .cmp-icon { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background: var(--prussian-blue); color: #fff; }
  .cmp-icon svg { width: 15px; height: 15px; }
  .cmp-table__footnote { max-width: 640px; margin: 20px auto 0; text-align: center; font-size: .78rem; color: var(--ink-soft); line-height: 1.5; }
  @media (max-width: 640px) { .cmp-table__cell--label { width: 128px; font-size: .78rem; padding-right: 4px; } .cmp-table__num { font-size: 1.2rem; } .cmp-icon { width: 26px; height: 26px; } }

  .hv { position: relative; width: 100%; }
  .hv__img-wrap { position: relative; border-radius: 26px; overflow: hidden; aspect-ratio: 4/5; box-shadow: 0 40px 80px -30px rgba(0,0,0,.55); }
  .hv__img-wrap img, .hv__img-wrap video { width: 100%; height: 100%; object-fit: cover; object-position: center 30%; filter: saturate(1.05) contrast(1.02); }
  .hv__img-wrap::after { content:""; position:absolute; inset:0; background:linear-gradient(180deg, rgba(5,15,35,0) 55%, rgba(5,15,35,.55) 100%); }
  .hv__chips { position: absolute; top: 22px; left: -18px; z-index: 2; display: flex; flex-direction: column; gap: 10px; }
  .hv__chip { display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,.96); backdrop-filter: blur(10px); border-radius: 999px; padding: 9px 16px 9px 10px; box-shadow: 0 14px 30px -12px rgba(10,21,41,.45); font-family: var(--font-display); font-weight: 600; font-size: .8rem; color: var(--prussian-blue); white-space: nowrap; }
  .hv__chip-check { flex: none; width: 20px; height: 20px; border-radius: 6px; background: var(--ocean-twilight); color: #fff; display: inline-flex; align-items: center; justify-content: center; }
  .hv__chip-check svg { width: 12px; height: 12px; }
  .hv__chip:nth-child(2) .hv__chip-check { background: var(--prussian-blue); }
  .hv__stat { position: absolute; top: -18px; right: -10px; z-index: 2; background: rgba(255,255,255,.94); backdrop-filter: blur(10px); border-radius: 18px; padding: 16px 20px; text-align: right; box-shadow: 0 20px 44px -16px rgba(10,21,41,.5); }
  .hv__stat-label { font-family: var(--font-display); font-weight: 700; font-size: .62rem; letter-spacing: .12em; text-transform: uppercase; color: var(--ink-soft); }
  .hv__stat-num { font-family: var(--font-display); font-weight: 800; font-size: 2.1rem; line-height: 1; color: var(--prussian-blue); margin-top: 6px; letter-spacing: -.02em; }
  .hv__stat-num span { color: var(--ocean-twilight); }
  .hv__stat-sub { font-size: .78rem; color: var(--ink-soft); margin-top: 4px; max-width: 12ch; margin-left: auto; }
  .hv__card { position: absolute; left: 50%; bottom: -26px; transform: translateX(-50%); z-index: 2; width: min(300px,90%); background: rgba(255,255,255,.96); backdrop-filter: blur(10px); border-radius: 16px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; box-shadow: 0 24px 50px -18px rgba(10,21,41,.5); }
  .hv__card-icon { flex: none; width: 38px; height: 38px; border-radius: 10px; background: var(--prussian-blue); color: var(--chartreuse); display: grid; place-items: center; }
  .hv__card-icon i { width: 18px; height: 18px; display: flex; }
  .hv__card-info { flex: 1; min-width: 0; }
  .hv__card-name { font-family: var(--font-display); font-weight: 700; font-size: .86rem; color: var(--prussian-blue); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .hv__card-rating { font-size: .74rem; color: var(--ink-soft); margin-top: 1px; }
  .hv__card-badge { flex: none; background: var(--chartreuse); color: var(--prussian-blue); font-family: var(--font-display); font-weight: 700; font-size: .62rem; letter-spacing: .02em; padding: 5px 9px; border-radius: 999px; white-space: nowrap; }
  .hero--industry .hv { width: 420px; margin: 0 auto; }
  @media (max-width: 980px) { .hero--industry .hv { width: 100%; max-width: 420px; } }
  @media (max-width: 640px) {
    .hero--industry .hero__visual { padding: 0 32px; }
    .hero--industry .hv { max-width: 300px; }
    .hv__chips { left: -10px; top: 16px; }
    .hv__chip { font-size: .7rem; padding: 7px 12px 7px 8px; }
    .hv__stat { right: -10px; top: -14px; padding: 12px 14px; }
    .hv__stat-num { font-size: 1.5rem; }
    .hv__card { bottom: -20px; padding: 10px 12px; }
    .hv__card-icon { width: 32px; height: 32px; }
  }
  .services-roof--compact { min-height: 620px; }
  .services-roof--compact .services-roof__inner { padding: 60px 0; }
  .case-studies .cs__card.is-active,
  .case-studies .cs__metrics-panel.is-active { opacity: 1; }
</style>
<?php wp_head(); ?>
</head>
<body>
<?php wp_body_open(); ?>

<!-- ===== NAV ===== -->
<header class="nav" id="nav">
  <div class="nav__inner">
    <a class="nav__logo" href="/" aria-label="Ranked International — home">
      <img src="<?php echo rip_asset('rankd-international-logo.png'); ?>" alt="Ranked International" class="nav__logo-img" width="96" height="32">
    </a>
    <nav class="nav__menu">
      <a href="/">Home</a>
      <div class="nav__mega-root">
        <a class="nav__mega-trigger" href="/#services">Services <i data-lucide="chevron-down" class="mega-chevron"></i></a>
        <div class="mega-menu" role="menu">
          <div class="mega-grid">
            <div>
              <p class="mega-label">Organic growth</p>
              <a class="mega-item" href="/#services"><span class="mega-icon"><i data-lucide="search"></i></span><span><strong>Local SEO</strong><span>Dominate Google Maps and the local pack for searches that drive booked jobs.</span></span></a>
              <a class="mega-item" href="/#services"><span class="mega-icon"><i data-lucide="map-pin"></i></span><span><strong>Google Business Profile</strong><span>Reviews, posts, and profile optimization to out-rank competitors nearby.</span></span></a>
              <a class="mega-item" href="/#services"><span class="mega-icon"><i data-lucide="settings-2"></i></span><span><strong>Technical SEO</strong><span>Site speed, crawlability, and schema fixes that lift your rankings fast.</span></span></a>
            </div>
            <div>
              <p class="mega-label">Paid &amp; content</p>
              <a class="mega-item" href="/#services"><span class="mega-icon"><i data-lucide="mouse-pointer-click"></i></span><span><strong>Google Ads (PPC)</strong><span>High-intent campaigns that bring in calls the same week you launch.</span></span></a>
              <a class="mega-item" href="/#services"><span class="mega-icon"><i data-lucide="pen-line"></i></span><span><strong>Content &amp; Blogging</strong><span>Keyword-targeted articles that build authority and capture long-tail traffic.</span></span></a>
              <a class="mega-item" href="/#services"><span class="mega-icon"><i data-lucide="link-2"></i></span><span><strong>Link Building</strong><span>White-hat backlinks from real sites that move your domain authority needle.</span></span></a>
            </div>
            <div>
              <p class="mega-label">Quick links</p>
              <div class="mega-links">
                <a href="/#audit">Free SEO audit <i data-lucide="arrow-right"></i></a>
                <a href="<?php echo esc_url( $hub_url ); ?>">See results <i data-lucide="arrow-right"></i></a>
                <a href="/#process">How we work <i data-lucide="arrow-right"></i></a>
              </div>
            </div>
            <a class="mega-card" href="/#audit">
              <img src="<?php echo rip_asset('hero-bg.jpg'); ?>" alt="Dallas skyline">
              <div class="mega-card-copy"><p>Free · No commitment</p><h3>Get your custom SEO audit today.</h3></div>
            </a>
          </div>
        </div>
      </div>
      <div class="nav__mega-root">
        <a class="nav__mega-trigger" href="/#industries">Industries We Serve <i data-lucide="chevron-down" class="mega-chevron"></i></a>
        <div class="mega-menu" role="menu">
          <div class="mega-grid">
            <div>
              <p class="mega-label">Home services</p>
              <a class="mega-item" href="/construction/"><span class="mega-icon"><i data-lucide="hard-hat"></i></span><span><strong>Construction</strong><span>Page-one rankings for GCs, remodelers, and commercial builders.</span></span></a>
              <a class="mega-item" href="/#industries"><span class="mega-icon"><i data-lucide="wind"></i></span><span><strong>HVAC</strong><span>Page-one visibility for heating and cooling crews when it matters most.</span></span></a>
              <a class="mega-item" href="/#industries"><span class="mega-icon"><i data-lucide="droplets"></i></span><span><strong>Plumbing</strong><span>Emergency and scheduled service traffic to fill your dispatch board.</span></span></a>
            </div>
            <div>
              <p class="mega-label">Professional services</p>
              <a class="mega-item" href="/#industries"><span class="mega-icon"><i data-lucide="stethoscope"></i></span><span><strong>Medical &amp; Dental</strong><span>Local SEO for practices that want a full schedule and less ad spend.</span></span></a>
              <a class="mega-item" href="/#industries"><span class="mega-icon"><i data-lucide="scale"></i></span><span><strong>Law Firms</strong><span>High-value keyword targeting for attorneys competing in a crowded market.</span></span></a>
              <a class="mega-item" href="/#industries"><span class="mega-icon"><i data-lucide="building-2"></i></span><span><strong>Real Estate</strong><span>Neighborhood and city-level rankings to capture buyer and seller leads.</span></span></a>
            </div>
            <div>
              <p class="mega-label">Quick links</p>
              <div class="mega-links">
                <a href="<?php echo esc_url( $hub_url ); ?>">Client results <i data-lucide="arrow-right"></i></a>
                <a href="/#audit">Free audit for your industry <i data-lucide="arrow-right"></i></a>
                <a href="/#process">Our process <i data-lucide="arrow-right"></i></a>
              </div>
            </div>
            <a class="mega-card" href="<?php echo esc_url( $hub_url ); ?>">
              <img src="<?php echo rip_asset('hero-bg.jpg'); ?>" alt="Dallas business results">
              <div class="mega-card-copy"><p>100+ businesses ranked</p><h3>See what we've done for Dallas businesses like yours.</h3></div>
            </a>
          </div>
        </div>
      </div>
      <a href="<?php echo esc_url( $hub_url ); ?>">Case Studies</a>
      <a href="<?php echo esc_url( home_url( '/blogs/' ) ); ?>">Blogs</a>
      <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
    </nav>
    <div class="nav__actions">
      <a href="tel:+16805542324" class="nav__phone">Dallas · (680) 554-2324</a>
      <a href="#audit" class="btn btn--primary btn--sm">Get my free audit</a>
    </div>
    <button class="nav__burger" id="navBurger" aria-label="Open menu" aria-expanded="false" aria-controls="navMenuMobile">
      <span></span><span></span><span></span>
    </button>
  </div>
  <nav class="nav__menu-mobile" id="navMenuMobile">
    <a href="/">Home</a>
    <a href="/#services">Services</a>
    <a href="/#industries">Industries We Serve</a>
    <a href="<?php echo esc_url( $hub_url ); ?>">Case Studies</a>
    <a href="<?php echo esc_url( home_url( '/blogs/' ) ); ?>">Blogs</a>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
    <a href="tel:+16805542324" class="nav__menu-mobile-phone">Dallas · (680) 554-2324</a>
    <a href="#audit" class="btn btn--primary btn--block">Get my free audit</a>
  </nav>
</header>

<main id="top">

  <!-- ===== 1. HERO ===== -->
  <section class="hero hero--industry" id="hero">
    <div class="hero__bg" aria-hidden="true">
      <div class="hero__aurora"></div>
      <div class="hero__grain"></div>
    </div>
    <div class="hero__inner">
      <div class="hero__copy">
        <div class="hero__card">
          <div class="hero__text-block">
            <?php if ( $eyebrow = get_field( 'hero_eyebrow' ) ) : ?><p class="hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
            <h1 class="hero__title"><?php echo wp_kses_post( get_field( 'hero_title' ) ); ?></h1>
            <?php if ( $sub = get_field( 'hero_sub' ) ) : ?><p class="hero__sub"><?php echo esc_html( $sub ); ?></p><?php endif; ?>
          </div>
          <div class="hero__bottom">
            <a href="#audit" class="btn btn--primary btn--lg">Get my free audit</a>
            <div class="hero__stats">
              <div class="hero__stat">
                <svg class="hero__stat-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 4H4v2l1.5 1.5V20h13V7.5L20 6V4zM7 18V8h10v10H7z" fill="#caff00"/><rect x="9" y="13" width="2" height="5" fill="#caff00"/><rect x="13" y="13" width="2" height="5" fill="#caff00"/><rect x="9" y="9" width="2" height="2" fill="#caff00"/><rect x="13" y="9" width="2" height="2" fill="#caff00"/></svg>
                <div class="hero__stat-text"><strong><?php echo esc_html( get_field( 'hero_stat1_value' ) ?: '100+' ); ?></strong><span><?php echo esc_html( get_field( 'hero_stat1_label' ) ?: 'Businesses Ranked' ); ?></span></div>
              </div>
              <div class="hero__stat">
                <svg class="hero__stat-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="#caff00"/></svg>
                <div class="hero__stat-text"><strong><?php echo esc_html( get_field( 'hero_stat2_value' ) ?: '5 Star' ); ?></strong><span><?php echo esc_html( get_field( 'hero_stat2_label' ) ?: 'Rated on Google' ); ?></span></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="hero__visual">
        <div class="hv">
          <div class="hv__img-wrap">
            <video autoplay muted loop playsinline disablepictureinpicture
                   <?php if ( $hero_video_poster ) : ?>poster="<?php echo esc_url( $hero_video_poster ); ?>"<?php endif; ?>
                   aria-label="<?php echo esc_attr( $industry_name ); ?> professional reviewing a project">
              <?php if ( $hero_video ) : ?><source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4"><?php endif; ?>
            </video>
          </div>
          <?php if ( have_rows( 'hero_chips' ) ) : ?>
          <div class="hv__chips">
            <?php while ( have_rows( 'hero_chips' ) ) : the_row(); ?>
            <div class="hv__chip">
              <span class="hv__chip-check"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
              <?php echo esc_html( get_sub_field( 'text' ) ); ?>
            </div>
            <?php endwhile; ?>
          </div>
          <?php endif; ?>
          <?php if ( $stat3v = get_field( 'hero_stat3_value' ) ) : ?>
          <div class="hv__stat">
            <p class="hv__stat-label"><?php echo esc_html( get_field( 'hero_stat3_label' ) ?: '— Up to' ); ?></p>
            <p class="hv__stat-num"><?php echo wp_kses_post( $stat3v ); ?></p>
            <p class="hv__stat-sub"><?php echo esc_html( get_field( 'hero_stat3_sub' ) ?: 'More leads booked' ); ?></p>
          </div>
          <?php endif; ?>
          <?php if ( $cardname = get_field( 'hero_card_name' ) ) : ?>
          <div class="hv__card">
            <div class="hv__card-icon"><i data-lucide="hard-hat"></i></div>
            <div class="hv__card-info">
              <p class="hv__card-name"><?php echo esc_html( $cardname ); ?></p>
              <p class="hv__card-rating"><?php echo esc_html( get_field( 'hero_card_rating' ) ); ?></p>
            </div>
            <span class="hv__card-badge"><?php echo esc_html( get_field( 'hero_card_badge' ) ?: '#1 on Google' ); ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <?php if ( have_rows( 'trusted_logos' ) ) : ?>
  <!-- ===== TRUSTED BY LOGO MARQUEE ===== -->
  <section class="trusted-bar">
    <div class="trusted-bar__inner">
      <span class="trusted-bar__label">Trusted by</span>
      <div class="trusted-bar__logos">
        <div class="trusted-bar__track" aria-hidden="true">
          <?php
          // rendered twice so the CSS marquee loop is seamless
          for ( $pass = 0; $pass < 2; $pass++ ) :
            while ( have_rows( 'trusted_logos' ) ) : the_row();
              $logo = get_sub_field( 'logo' );
              if ( $logo ) : ?>
              <img src="<?php echo esc_url( $logo ); ?>" alt="" width="120" height="40" loading="lazy">
              <?php endif;
            endwhile;
          endfor;
          ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if ( have_rows( 'proof_stats' ) ) : ?>
  <!-- ===== PROOF / STATS ===== -->
  <section class="proof" id="proof">
    <div class="proof__inner">
      <div class="section-head">
        <?php if ( $pe = get_field( 'proof_eyebrow' ) ) : ?><p class="eyebrow"><span class="dot"></span><?php echo esc_html( $pe ); ?></p><?php endif; ?>
        <?php if ( $pt = get_field( 'proof_title' ) ) : ?><h2 class="section-title"><?php echo wp_kses_post( $pt ); ?></h2><?php endif; ?>
      </div>
      <div class="proof__grid">
        <?php while ( have_rows( 'proof_stats' ) ) : the_row(); ?>
        <div class="proof__card">
          <p class="proof__num"><?php echo esc_html( get_sub_field( 'num' ) ); ?><span><?php echo esc_html( get_sub_field( 'suffix' ) ); ?></span></p>
          <p class="proof__label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></p>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if ( get_field( 'segments_title' ) || have_rows( 'segments' ) ) : ?>
  <!-- ===== WHO WE RANK — SEGMENTS ===== -->
  <section class="segments" id="segments">
    <div class="segments__inner">
      <div class="section-head">
        <?php if ( $st = get_field( 'segments_title' ) ) : ?><h2 class="section-title"><?php echo wp_kses_post( $st ); ?></h2><?php endif; ?>
      </div>
      <?php if ( $banner = get_field( 'segments_banner' ) ) : ?>
      <div class="segments__banner">
        <img src="<?php echo esc_url( $banner ); ?>" alt="" loading="lazy" width="1600" height="480">
      </div>
      <?php endif; ?>
      <?php if ( have_rows( 'segments' ) ) : ?>
      <div class="segments__grid">
        <?php while ( have_rows( 'segments' ) ) : the_row(); ?>
        <div class="segment-card">
          <div class="segment-card__icon"><i data-lucide="<?php echo esc_attr( get_sub_field( 'icon' ) ?: 'hard-hat' ); ?>"></i></div>
          <h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
          <p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
          <?php if ( $kw = get_sub_field( 'keywords' ) ) : ?><p class="segment-card__kw">Ranks for: <span><?php echo esc_html( $kw ); ?></span></p><?php endif; ?>
        </div>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if ( get_field( 'compare_title' ) || have_rows( 'compare_rows' ) ) : ?>
  <!-- ===== COMPARISON TABLE ===== -->
  <section class="compare" id="compare">
    <div class="compare__inner">
      <div class="compare__head section-head">
        <?php if ( $ct = get_field( 'compare_title' ) ) : ?><h2 class="section-title"><?php echo wp_kses_post( $ct ); ?></h2><?php endif; ?>
        <?php if ( $cs = get_field( 'compare_subhead' ) ) : ?><p class="compare__subhead"><?php echo esc_html( $cs ); ?></p><?php endif; ?>
      </div>
      <div class="cmp-table">
        <div class="cmp-table__body">
          <div class="cmp-table__row cmp-table__row--head">
            <div class="cmp-table__cell cmp-table__cell--label"></div>
            <div class="cmp-table__cell cmp-table__cell--hi"><span class="cmp-table__col-head">Ranked<br>International</span></div>
            <div class="cmp-table__cell"><span class="cmp-table__col-head" style="color:var(--ink-soft)">Typical<br>SEO Agency</span></div>
          </div>
          <?php
          $compare_rows = get_field( 'compare_rows' );
          $compare_row_count = is_array( $compare_rows ) ? count( $compare_rows ) : 0;
          while ( have_rows( 'compare_rows' ) ) : the_row();
            $type = get_sub_field( 'type' ) ?: 'metric';
            $ranked = get_sub_field( 'ranked_value' );
            $agency = get_sub_field( 'agency_value' );
            $is_last_compare_row = get_row_index() === $compare_row_count;
          ?>
          <div class="cmp-table__row<?php echo $is_last_compare_row ? ' cmp-table__row--last' : ''; ?>">
            <div class="cmp-table__cell cmp-table__cell--label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></div>
            <div class="cmp-table__cell cmp-table__cell--hi">
              <?php if ( $type === 'check' ) : ?>
                <?php if ( strtolower( $ranked ) === 'yes' ) : ?><span class="cmp-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span><?php else : ?><span class="cmp-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></span><?php endif; ?>
              <?php else : ?>
                <span class="cmp-table__num"><?php echo esc_html( $ranked ); ?></span>
              <?php endif; ?>
            </div>
            <div class="cmp-table__cell">
              <?php if ( $type === 'check' ) : ?>
                <?php if ( strtolower( $agency ) === 'yes' ) : ?><span class="cmp-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span><?php else : ?><span class="cmp-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></span><?php endif; ?>
              <?php else : ?>
                <span class="cmp-table__num" style="color:var(--ink-soft)"><?php echo esc_html( $agency ); ?></span>
              <?php endif; ?>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
      </div>
      <?php if ( $fn = get_field( 'compare_footnote' ) ) : ?><p class="cmp-table__footnote"><?php echo esc_html( $fn ); ?></p><?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php $spotlights = get_field( 'spotlights' ); if ( $spotlights ) : // plain get_field — an un-iterated have_rows() would leave a dangling ACF loop ?>
  <!-- ===== CLIENT SPOTLIGHT CAROUSEL ===== -->
  <section class="case-studies" id="results">
    <div class="cs__inner">
      <div class="cs__header">
        <div class="cs__header-left">
          <p class="eyebrow"><span class="dot"></span><?php echo esc_html( get_field( 'spotlight_eyebrow' ) ?: 'Client results' ); ?></p>
          <?php if ( $spt = get_field( 'spotlight_title' ) ) : ?><h2 class="cs__title"><?php echo wp_kses_post( $spt ); ?></h2><?php endif; ?>
        </div>
        <a href="<?php echo esc_url( $hub_url ); ?>" class="cs__see-all">&#9658;&nbsp;See all results</a>
      </div>
      <div class="cs__layout">
        <div class="cs__center">
          <div class="cs__stage">
            <?php foreach ( $spotlights as $idx => $slide ) : ?>
            <article class="cs__card<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-cs-idx="<?php echo esc_attr( $idx ); ?>">
              <span class="cs__card-kicker"><?php echo esc_html( $slide['kicker'] ); ?></span>
              <blockquote class="cs__quote"><?php echo wp_kses_post( $slide['quote'] ); ?></blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client"><?php echo esc_html( $slide['client'] ); ?></p>
                <a href="#audit" class="cs__cta-link">&#9658;&nbsp;Get your free audit</a>
              </footer>
            </article>
            <?php endforeach; ?>
          </div>
          <?php if ( count( $spotlights ) > 1 ) : ?>
          <div class="cs__controls">
            <button class="cs__arrow" data-cs-prev aria-label="Previous case study"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M11 4L6 9l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            <div class="cs__pips" aria-hidden="true"><?php foreach ( $spotlights as $slide ) : ?><span class="cs__pip"></span><?php endforeach; ?></div>
            <button class="cs__arrow" data-cs-next aria-label="Next case study"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
          </div>
          <?php endif; ?>
        </div>
        <div class="cs__metrics">
          <?php foreach ( $spotlights as $idx => $slide ) : ?>
          <div class="cs__metrics-panel<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-cs-metrics="<?php echo esc_attr( $idx ); ?>">
            <div class="cs__metric-block">
              <p class="cs__metric-label"><?php echo esc_html( $slide['metric1_label'] ); ?></p>
              <p class="cs__metric-num"><?php echo wp_kses_post( $slide['metric1_value'] ); ?></p>
              <p class="cs__metric-sub"><?php echo esc_html( $slide['metric1_sub'] ); ?></p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label"><?php echo esc_html( $slide['metric2_label'] ); ?></p>
              <p class="cs__metric-num"><?php echo wp_kses_post( $slide['metric2_value'] ); ?></p>
              <p class="cs__metric-sub"><?php echo esc_html( $slide['metric2_sub'] ); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if ( have_rows( 'services' ) ) : ?>
  <!-- ===== SERVICES WIRING DIAGRAM ===== -->
  <section class="services-roof services-roof--compact" id="services" aria-label="All SEO and paid services for <?php echo esc_attr( $industry_name ); ?> companies">
    <div class="services-roof__inner">
      <h2><?php echo esc_html( get_field( 'services_title' ) ?: 'Every Service Under One Roof For Your Trade' ); ?></h2>
      <div class="services-roof__map">
        <svg class="services-roof__lines" viewBox="0 0 934 458" preserveAspectRatio="none" aria-hidden="true">
          <path d="M106 94 C106 154 156 175 220 175 H410 C455 175 467 199 467 229" />
          <path d="M467 94 V229" />
          <path d="M815 94 C815 154 765 175 701 175 H524 C480 175 467 199 467 229" />
          <path d="M106 373 C106 314 156 287 220 287 H410 C455 287 467 259 467 229" />
          <path d="M467 373 V229" />
          <path d="M815 373 C815 314 765 287 701 287 H524 C480 287 467 259 467 229" />
        </svg>
        <a href="#audit" class="services-roof__center"><?php echo esc_html( get_field( 'services_center' ) ?: 'Get You Ranked' ); ?></a>
        <?php
        $chip_classes = array( 'local', 'technical', 'organic', 'link', 'ads', 'ppc' );
        $i = 0;
        while ( have_rows( 'services' ) ) : the_row();
          $cls = $chip_classes[ $i % count( $chip_classes ) ]; $i++;
          $icon = get_sub_field( 'icon' );
        ?>
        <div class="services-roof__chip services-roof__chip--<?php echo esc_attr( $cls ); ?>">
          <div class="services-roof__chip-head">
            <?php if ( $icon ) : ?><i data-lucide="<?php echo esc_attr( $icon ); ?>"></i><?php endif; ?>
            <span><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
          </div>
          <p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if ( have_rows( 'process_steps' ) ) : ?>
  <!-- ===== PROCESS ===== -->
  <section class="process" id="process">
    <div class="process__head">
      <p class="eyebrow eyebrow--light"><span class="dot"></span><?php echo esc_html( get_field( 'process_eyebrow' ) ?: 'How it works' ); ?></p>
    </div>
    <ol class="process__steps" id="processSteps">
      <?php while ( have_rows( 'process_steps' ) ) : the_row(); ?>
      <li class="process__step">
        <div class="process__step-inner">
          <div class="process__icon-wrap">
            <span class="process__dot"></span>
            <span class="process__icon"><i data-lucide="<?php echo esc_attr( get_sub_field( 'icon' ) ?: 'search' ); ?>"></i></span>
          </div>
          <h3 class="process__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
          <div class="process__right">
            <p class="process__desc"><?php echo esc_html( get_sub_field( 'desc' ) ); ?></p>
            <a href="#audit" class="process__btn">Get free audit <i data-lucide="arrow-right"></i></a>
          </div>
        </div>
      </li>
      <?php endwhile; ?>
    </ol>
    <a href="#audit" class="process__btn-mobile">Get free audit <i data-lucide="arrow-right"></i></a>
  </section>
  <?php endif; ?>

  <?php if ( have_rows( 'faqs' ) ) : ?>
  <!-- ===== FAQ ===== -->
  <section class="faq" id="faq">
    <div class="faq__head">
      <?php if ( $ft = get_field( 'faq_title' ) ) : ?><h2><?php echo esc_html( $ft ); ?></h2><?php endif; ?>
      <?php if ( $fs = get_field( 'faq_sub' ) ) : ?><p><?php echo esc_html( $fs ); ?></p><?php endif; ?>
    </div>
    <div class="faq__list">
      <?php $i = 0; while ( have_rows( 'faqs' ) ) : the_row(); $i++; ?>
      <article class="faq__item<?php echo $i === 1 ? ' is-open' : ''; ?>">
        <button class="faq__question" type="button" aria-expanded="<?php echo $i === 1 ? 'true' : 'false'; ?>">
          <span><?php echo esc_html( get_sub_field( 'question' ) ); ?></span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="faq__answer"><p><?php echo esc_html( get_sub_field( 'answer' ) ); ?></p></div>
      </article>
      <?php endwhile; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- ===== FINAL CTA ===== -->
  <section class="cta" id="cta-final">
    <div class="cta__inner">
      <div class="cta__copy">
        <p class="cta__title"><?php echo wp_kses_post( get_field( 'cta_title' ) ?: 'Let\'s get your phone <em>ringing</em>.' ); ?></p>
        <p class="cta__sub"><?php echo esc_html( get_field( 'cta_sub' ) ?: 'Get a free audit showing the 3 keywords your competitors are taking customers from right now.' ); ?></p>
      </div>
      <div style="display:flex; flex-direction:column; gap:14px; align-items:flex-start;">
        <a href="#audit" class="btn btn--dark btn--lg">Get my free audit</a>
        <a href="tel:+16805542324" style="font-family:var(--font-display); font-weight:600; color:var(--prussian-blue);">Or call Dallas &middot; (680) 554-2324</a>
      </div>
    </div>
  </section>

</main>

<div class="audit-modal" id="auditModal" aria-hidden="true">
  <div class="audit-modal__backdrop" data-audit-close></div>
  <div class="audit-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="auditTitle">
    <button class="audit-modal__close" type="button" data-audit-close aria-label="Close audit form">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
    <form class="audit-modal__form" id="auditForm">
      <label class="rip-honeypot" aria-hidden="true" style="position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden">Company fax<input type="text" name="company_fax" tabindex="-1" autocomplete="off"></label>
      <div class="audit-modal__step is-active" data-audit-step="1">
        <p class="audit-modal__eyebrow">Step 1 of 2</p>
        <h2 id="auditTitle">Start your free audit</h2>
        <p class="audit-modal__copy">Tell us where to send the quick findings.</p>
        <label class="audit-field"><span>Name</span><input type="text" name="name" autocomplete="name" required></label>
        <label class="audit-field"><span>Email</span><input type="email" name="email" autocomplete="email" required></label>
        <button type="button" class="btn btn--dark btn--block" data-audit-next>Continue</button>
      </div>
      <div class="audit-modal__step" data-audit-step="2">
        <p class="audit-modal__eyebrow">Step 2 of 2</p>
        <h2>What should we inspect?</h2>
        <p class="audit-modal__copy">A website and one priority is enough to start.</p>
        <label class="audit-field"><span>Website</span><input type="url" name="website" autocomplete="url" placeholder="https://example.com" required></label>
        <label class="audit-field audit-field--select"><span>Primary goal</span>
          <select name="service" required>
            <option value="" disabled selected>Select one</option>
            <option>Local SEO</option>
            <option>Organic SEO</option>
            <option>Technical SEO</option>
            <option>Google Ads / PPC</option>
            <option>Not sure yet</option>
          </select>
        </label>
        <label class="audit-field"><span>Anything we should know?</span><textarea name="notes" rows="4" placeholder="Competitors, target city, current issue..."></textarea></label>
        <div class="audit-modal__actions">
          <button type="button" class="btn btn--outline" data-audit-back>Back</button>
          <button type="submit" class="btn btn--dark">Submit audit</button>
        </div>
      </div>
      <div class="audit-modal__step audit-modal__done" data-audit-step="done">
        <div class="audit-modal__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg></div>
        <h2>Audit request received</h2>
        <p class="audit-modal__copy">We'll review the site and send the first findings within 24 hours.</p>
        <button type="button" class="btn btn--primary btn--block" data-audit-close>Done</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="footer__inner">
    <div class="footer__brand">
      <a class="nav__logo" href="/"><img src="<?php echo rip_asset('rankd-international-logo.png'); ?>" alt="Ranked International" class="nav__logo-img" width="96" height="32" loading="lazy"></a>
      <p>Dallas SEO that gets the phone ringing. One client per industry.</p>
      <p class="footer__addr">Dallas, TX · (680) 554-2324</p>
    </div>
    <div class="footer__cols">
      <div><h3>SEO</h3><a href="/#services">Local SEO</a><a href="/#services">Organic SEO</a><a href="/#services">Technical SEO</a><a href="/#services">Enterprise SEO</a><a href="/#services">Link Building</a></div>
      <div><h3>Paid</h3><a href="/#services">Google Ads</a><a href="/#services">PPC</a><a href="/#services">Enterprise PPC</a></div>
      <div><h3>Consulting</h3><a href="/#services">SEO Consulting</a><a href="/#services">CRO Audit</a></div>
      <div><h3>Company</h3><a href="<?php echo esc_url( $hub_url ); ?>">Results</a><a href="/#process">About</a><a href="/construction/">Construction SEO</a><a href="<?php echo esc_url( home_url( '/blogs/' ) ); ?>">Blogs</a><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></div>
    </div>
  </div>
  <div class="footer__base"><span>&copy; 2026 Ranked International</span><span>Dallas, Texas</span></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lucide@1.23.0/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
<script>
(function(){
  if (typeof gsap === 'undefined') return;
  gsap.registerPlugin(ScrollTrigger);
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  function fadeUpGroup(selector, opts) {
    var els = gsap.utils.toArray(selector);
    if (!els.length) return;
    opts = opts || {};
    if (reduce) { gsap.set(els, { opacity: 1, y: 0 }); return; }
    gsap.from(els, {
      scrollTrigger: { trigger: els[0].closest('section') || els[0], start: opts.start || 'top 85%' },
      y: opts.y || 28,
      opacity: 0,
      duration: opts.duration || 0.7,
      ease: 'power3.out',
      stagger: opts.stagger || 0.1
    });
  }

  fadeUpGroup('.proof__card', { y: 24, stagger: 0.12 });
  fadeUpGroup('.segment-card', { y: 28, stagger: 0.1 });
  fadeUpGroup('.cmp-table__row', { y: 14, duration: 0.5, stagger: 0.06 });

  var hvBits = gsap.utils.toArray('.hv__chip, .hv__stat, .hv__card');
  if (hvBits.length) {
    if (reduce) {
      gsap.set(hvBits, { opacity: 1, scale: 1 });
    } else {
      gsap.set(hvBits, { opacity: 0, scale: 0.85 });
      gsap.to(hvBits, {
        opacity: 1, scale: 1, duration: 0.55, ease: 'back.out(1.7)',
        stagger: 0.12, delay: 1.1
      });
    }
  }
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
