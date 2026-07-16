<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet"></noscript>


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
        <a class="nav__mega-trigger" href="/#services">Services
          <i data-lucide="chevron-down" class="mega-chevron"></i>
        </a>
        <div class="mega-menu" role="menu" aria-label="Services menu">
          <div class="mega-grid">
            <div>
              <p class="mega-label">Organic growth</p>
              <a class="mega-item" href="/#services">
                <span class="mega-icon"><i data-lucide="search"></i></span>
                <span><strong>Local SEO</strong><span>Dominate Google Maps and the local pack for searches that drive booked jobs.</span></span>
              </a>
              <a class="mega-item" href="/#services">
                <span class="mega-icon"><i data-lucide="map-pin"></i></span>
                <span><strong>Google Business Profile</strong><span>Reviews, posts, and profile optimization to out-rank competitors nearby.</span></span>
              </a>
              <a class="mega-item" href="/#services">
                <span class="mega-icon"><i data-lucide="settings-2"></i></span>
                <span><strong>Technical SEO</strong><span>Site speed, crawlability, and schema fixes that lift your rankings fast.</span></span>
              </a>
            </div>
            <div>
              <p class="mega-label">Paid &amp; content</p>
              <a class="mega-item" href="/#services">
                <span class="mega-icon"><i data-lucide="mouse-pointer-click"></i></span>
                <span><strong>Google Ads (PPC)</strong><span>High-intent campaigns that bring in calls the same week you launch.</span></span>
              </a>
              <a class="mega-item" href="/#services">
                <span class="mega-icon"><i data-lucide="pen-line"></i></span>
                <span><strong>Content &amp; Blogging</strong><span>Keyword-targeted articles that build authority and capture long-tail traffic.</span></span>
              </a>
              <a class="mega-item" href="/#services">
                <span class="mega-icon"><i data-lucide="link-2"></i></span>
                <span><strong>Link Building</strong><span>White-hat backlinks from real sites that move your domain authority needle.</span></span>
              </a>
            </div>
            <div>
              <p class="mega-label">Quick links</p>
              <div class="mega-links">
                <a href="#audit">Free SEO audit <i data-lucide="arrow-right"></i></a>
                <a href="/case-studies/">See results <i data-lucide="arrow-right"></i></a>
                <a href="/#process">How we work <i data-lucide="arrow-right"></i></a>
              </div>
            </div>
            <a class="mega-card" href="#audit">
              <img src="<?php echo rip_asset('hero-bg.jpg'); ?>" alt="Dallas skyline representing local SEO results">
              <div class="mega-card-copy">
                <p>Free · No commitment</p>
                <h3>Get your custom SEO audit today.</h3>
              </div>
            </a>
          </div>
        </div>
      </div>

      <div class="nav__mega-root">
        <a class="nav__mega-trigger" href="/#industries">Industries We Serve
          <i data-lucide="chevron-down" class="mega-chevron"></i>
        </a>
        <div class="mega-menu" role="menu" aria-label="Industries We Serve menu">
          <div class="mega-grid">
            <div>
              <p class="mega-label">Home &amp; trade services</p>
              <a class="mega-item" href="/construction/">
                <span class="mega-icon"><i data-lucide="hard-hat"></i></span>
                <span><strong>Construction</strong><span>Page-one rankings for GCs, remodelers, and commercial builders booking more jobs.</span></span>
              </a>
              <a class="mega-item" href="/#industries">
                <span class="mega-icon"><i data-lucide="wind"></i></span>
                <span><strong>HVAC</strong><span>Page-one visibility for heating and cooling crews when it matters most.</span></span>
              </a>
            </div>
            <div>
              <p class="mega-label">Professional services</p>
              <a class="mega-item" href="/#industries">
                <span class="mega-icon"><i data-lucide="stethoscope"></i></span>
                <span><strong>Medical &amp; Dental</strong><span>Local SEO for practices that want a full schedule and less ad spend.</span></span>
              </a>
              <a class="mega-item" href="/#industries">
                <span class="mega-icon"><i data-lucide="scale"></i></span>
                <span><strong>Law Firms</strong><span>High-value keyword targeting for attorneys competing in a crowded market.</span></span>
              </a>
              <a class="mega-item" href="/#industries">
                <span class="mega-icon"><i data-lucide="building-2"></i></span>
                <span><strong>Real Estate</strong><span>Neighborhood and city-level rankings to capture buyer and seller leads.</span></span>
              </a>
            </div>
            <div>
              <p class="mega-label">Quick links</p>
              <div class="mega-links">
                <a href="/case-studies/">Client results <i data-lucide="arrow-right"></i></a>
                <a href="#audit">Free audit for your industry <i data-lucide="arrow-right"></i></a>
                <a href="/#process">Our process <i data-lucide="arrow-right"></i></a>
              </div>
            </div>
            <a class="mega-card" href="/case-studies/">
              <img src="<?php echo rip_asset('hero-bg.jpg'); ?>" alt="Dallas business results">
              <div class="mega-card-copy">
                <p>100+ businesses ranked</p>
                <h3>See what we've done for Dallas businesses like yours.</h3>
              </div>
            </a>
          </div>
        </div>
      </div>

      <a href="/case-studies/">Case Studies</a>
      <a href="#audit">Contact</a>
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
    <a href="/case-studies/">Case Studies</a>
    <a href="#audit">Contact</a>
    <a href="tel:+16805542324" class="nav__menu-mobile-phone">Dallas · (680) 554-2324</a>
    <a href="#audit" class="btn btn--primary btn--block">Get my free audit</a>
  </nav>
</header>

<!-- ===== HUB HERO ===== -->
<section class="csp-hub-hero">
  <div class="csp-hub-hero__inner">
    <h1>Real Dallas businesses. <em>Real numbers.</em></h1>
    <p>Every stat on this page comes from live rank tracking and traffic data &mdash; from a moving company twelve months into its first campaign to a photo booth brand ranking #1 in 24 cities.</p>
  </div>
</section>

<!-- ===== GRID ===== -->
<section class="csp-hub-grid-section">
  <div class="csp-hub-grid-section__inner">
    <div class="csp-hub-grid">

<?php
      $case_studies = new WP_Query( array(
        'post_type'      => 'rip_case_study',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
      ) );
      $card_i = 0;
      while ( $case_studies->have_posts() ) : $case_studies->the_post();
        $card_i++;
        $logo    = get_field( 'client_logo' );
        $name    = get_field( 'client_name' ) ?: get_the_title();
        $tag     = get_field( 'industry_tag' );
        $title   = get_field( 'hero_title' );
        $sub     = get_field( 'hero_sub' );
        $metrics = get_field( 'hero_stats' ) ?: array();
        $featured = $card_i === 1;
      ?>
      <a class="csp-hub-card<?php echo $featured ? ' csp-hub-card--featured' : ''; ?>" href="<?php the_permalink(); ?>">
        <?php if ( $featured ) : ?>
        <?php if ( $logo ) : ?><img class="csp-hub-card__logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $name ); ?> logo"><?php endif; ?>
        <div class="csp-hub-card__body">
          <?php if ( $tag ) : ?><span class="csp-hub-card__tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
          <h3><?php echo wp_kses_post( $title ); ?></h3>
          <?php if ( $sub ) : ?><p><?php echo esc_html( $sub ); ?></p><?php endif; ?>
          <div class="csp-hub-card__stats">
            <?php foreach ( array_slice( $metrics, 0, 3 ) as $m ) : ?>
            <div class="csp-hub-card__stat"><strong><?php echo wp_kses_post( $m['value'] ); ?></strong><span><?php echo esc_html( $m['label'] ); ?></span></div>
            <?php endforeach; ?>
          </div>
          <span class="csp-hub-card__link">Read the case study <i data-lucide="arrow-right" style="width:14px;height:14px"></i></span>
        </div>
        <?php else : ?>
        <div class="csp-hub-card__top">
          <?php if ( $logo ) : ?><img class="csp-hub-card__logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $name ); ?> logo"><?php endif; ?>
          <?php if ( $tag ) : ?><span class="csp-hub-card__tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
        </div>
        <h3><?php echo wp_kses_post( $title ); ?></h3>
        <?php if ( $sub ) : ?><p><?php echo esc_html( $sub ); ?></p><?php endif; ?>
        <div class="csp-hub-card__stats">
          <?php foreach ( array_slice( $metrics, 0, 2 ) as $m ) : ?>
          <div class="csp-hub-card__stat"><strong><?php echo wp_kses_post( $m['value'] ); ?></strong><span><?php echo esc_html( $m['label'] ); ?></span></div>
          <?php endforeach; ?>
        </div>
        <span class="csp-hub-card__link">Read the case study <i data-lucide="arrow-right" style="width:14px;height:14px"></i></span>
        <?php endif; ?>
      </a>
      <?php endwhile; wp_reset_postdata(); ?>

    </div>
  </div>
</section>

<!-- ===== FINAL CTA ===== -->
<section class="cta" id="cta-final">
  <div class="cta__inner">
    <div class="cta__copy">
      <p class="cta__title">Want to be the next <em>case study</em>?</p>
      <p class="cta__sub">Get a free audit showing the 3 keywords your competitors are taking customers from right now.</p>
    </div>
    <div style="display:flex; flex-direction:column; gap:14px; align-items:flex-start;">
      <a href="#audit" class="btn btn--dark btn--lg">Get my free audit</a>
      <a href="tel:+16805542324" style="font-family:var(--font-display); font-weight:600; color:var(--prussian-blue);">Or call Dallas &middot; (680) 554-2324</a>
    </div>
  </div>
</section>

<!-- ===== AUDIT MODAL ===== -->
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
        <label class="audit-field">
          <span>Name</span>
          <input type="text" name="name" autocomplete="name" required>
        </label>
        <label class="audit-field">
          <span>Email</span>
          <input type="email" name="email" autocomplete="email" required>
        </label>
        <div class="audit-modal__actions">
          <button type="button" class="btn btn--primary btn--block" data-audit-next>Continue</button>
        </div>
      </div>

      <div class="audit-modal__step" data-audit-step="2">
        <p class="audit-modal__eyebrow">Step 2 of 2</p>
        <h2>A bit about your business</h2>
        <label class="audit-field">
          <span>Website</span>
          <input type="url" name="website" autocomplete="url" placeholder="https://example.com" required>
        </label>
        <label class="audit-field audit-field--select">
          <span>Primary goal</span>
          <select name="service" required>
            <option value="" disabled selected>Select one</option>
            <option>Local SEO</option>
            <option>Organic SEO</option>
            <option>Technical SEO</option>
            <option>Google Ads / PPC</option>
            <option>Not sure yet</option>
          </select>
        </label>
        <label class="audit-field">
          <span>Anything we should know?</span>
          <textarea name="notes" rows="4" placeholder="Competitors, target city, current issue..."></textarea>
        </label>
        <div class="audit-modal__actions">
          <button type="button" class="btn btn--outline" data-audit-back>Back</button>
          <button type="submit" class="btn btn--dark">Submit audit</button>
        </div>
      </div>

      <div class="audit-modal__step audit-modal__done" data-audit-step="done">
        <div class="audit-modal__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h2>Audit request received</h2>
        <p class="audit-modal__copy">We’ll review the site and send the first findings within 24 hours.</p>
        <button type="button" class="btn btn--primary btn--block" data-audit-close>Done</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="footer__inner">
    <div class="footer__brand">
      <a class="nav__logo" href="/" aria-label="Ranked International — home"><img src="<?php echo rip_asset('rankd-international-logo.png'); ?>" alt="Ranked International" class="nav__logo-img" width="96" height="32" loading="lazy"></a>
      <p>Dallas SEO that gets the phone ringing. One client per industry.</p>
      <p class="footer__addr">Dallas, TX · (xxx) xxx-xxxx</p>
    </div>
    <div class="footer__cols">
      <div><h3>SEO</h3><a href="/#services">Local SEO</a><a href="/#services">Organic SEO</a><a href="/#services">Technical SEO</a><a href="/#services">Enterprise SEO</a><a href="/#services">Link Building</a></div>
      <div><h3>Paid</h3><a href="/#services">Google Ads</a><a href="/#services">PPC</a><a href="/#services">Enterprise PPC</a></div>
      <div><h3>Consulting</h3><a href="/#services">SEO Consulting</a><a href="/#services">CRO Audit</a></div>
      <div><h3>Company</h3><a href="/case-studies/">Results</a><a href="/#process">About</a><a href="/construction/">Construction SEO</a><a href="#audit">Contact</a></div>
    </div>
  </div>
  <div class="footer__base"><span>© 2026 Ranked International</span><span>Dallas, Texas</span></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lucide@1.23.0/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
<?php wp_footer(); ?>
</body>
</html>
