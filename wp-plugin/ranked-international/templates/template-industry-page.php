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
$seo_title      = get_field( 'seo_title' ) ?: ( $industry_name . ' SEO Services — Ranked International' );
$seo_description = get_field( 'seo_description' );
$hero_video_poster = get_field( 'hero_video_poster' );
$hero_video     = get_field( 'hero_video' );
$hub_url        = rip_url_for_template( 'templates/template-case-studies-hub.php', '/case-studies/' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo esc_html( $seo_title ); ?></title>
<?php if ( $seo_description ) : ?><meta name="description" content="<?php echo esc_attr( $seo_description ); ?>" /><?php endif; ?>
<link rel="canonical" href="<?php echo esc_url( get_permalink() ); ?>" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet"></noscript>

<style>
  /* Industry-page-only overrides — shorter hero */
  .hero--industry { height: 600px; }
  .hero--industry .hero__copy { padding-top: 80px; }
  .hero--industry .hero__title { font-size: clamp(2.6rem, 6vw, 3.4rem); max-width: 16ch; }
  .hero--industry .hero__sub { max-width: 42ch; }
  @media (max-width: 980px) { .hero--industry .hero__copy { padding-top: 120px; } }
  @media (max-width: 640px) { .hero--industry { height: auto; } .hero--industry .hero__title { font-size: 26px; } }
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
      <a href="/#audit">Contact</a>
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
    <a href="#audit">Contact</a>
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
          <?php while ( have_rows( 'compare_rows' ) ) : the_row();
            $type = get_sub_field( 'type' ) ?: 'metric';
            $ranked = get_sub_field( 'ranked_value' );
            $agency = get_sub_field( 'agency_value' );
          ?>
          <div class="cmp-table__row">
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

  <?php if ( have_rows( 'spotlights' ) ) : $spotlights = get_field( 'spotlights' ); ?>
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
            <article class="cs__card<?php echo $idx === 0 ? '' : ''; ?>" data-cs-idx="<?php echo esc_attr( $idx ); ?>">
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
        <div class="services-roof__center"><?php echo esc_html( get_field( 'services_center' ) ?: 'Get You Ranked' ); ?></div>
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
      <div><h3>Company</h3><a href="<?php echo esc_url( $hub_url ); ?>">Results</a><a href="/#process">About</a><a href="/construction/">Construction SEO</a><a href="#audit">Contact</a></div>
    </div>
  </div>
  <div class="footer__base"><span>&copy; 2026 Ranked International</span><span>Dallas, Texas</span></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lucide@1.23.0/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
<?php wp_footer(); ?>
</body>
</html>
