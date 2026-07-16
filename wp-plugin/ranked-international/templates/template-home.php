<?php get_header(); ?>

<main id="top">

  <!-- ===== 1. HERO ===== -->
  <section class="hero" id="hero">
    <div class="hero__bg" aria-hidden="true">
      <div class="hero__aurora"></div>
      <div class="hero__grain"></div>
    </div>
    <div class="hero__inner">
      <div class="hero__copy">
        <div class="hero__card">
          <div class="hero__text-block">
            <p class="hero__eyebrow">DALLAS SEO AGENCY</p>
            <h1 class="hero__title">
              Your customers are Googling. You're on page&nbsp;2.
            </h1>
            <p class="hero__sub">
              We move Dallas roofers, HVAC crews, and clinics from buried on page two
              to the top of Google, then turn those clicks into booked jobs.
            </p>
          </div>
          <div class="hero__bottom">
            <a href="#audit" class="btn btn--primary btn--lg">Get my free audit</a>
            <div class="hero__stats">
              <div class="hero__stat">
                <svg class="hero__stat-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M20 4H4v2l1.5 1.5V20h13V7.5L20 6V4zM7 18V8h10v10H7z" fill="#caff00"/>
                  <rect x="9" y="13" width="2" height="5" fill="#caff00"/>
                  <rect x="13" y="13" width="2" height="5" fill="#caff00"/>
                  <rect x="9" y="9" width="2" height="2" fill="#caff00"/>
                  <rect x="13" y="9" width="2" height="2" fill="#caff00"/>
                </svg>
                <div class="hero__stat-text">
                  <strong>100+</strong>
                  <span>Businesses Ranked</span>
                </div>
              </div>
              <div class="hero__stat">
                <svg class="hero__stat-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="#caff00"/>
                </svg>
                <div class="hero__stat-text">
                  <strong>5 Star</strong>
                  <span>Rated on Google</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Hero visual: animated GIF showing a local business climbing to #1 on Google -->
      <div class="hero__visual">
        <video class="hero__gif" autoplay muted playsinline disablepictureinpicture
               aria-label="Google search results showing a local business rising to position #1">
          <source src="<?php echo rip_asset('frames/hero.webm'); ?>" type="video/webm">
          <source src="<?php echo rip_asset('frames/hero.mp4'); ?>"  type="video/mp4">
        </video>
      </div>
    </div>
  </section>

  <!-- ===== TRUSTED BY LOGO MARQUEE ===== -->
  <section class="trusted-bar">
    <div class="trusted-bar__inner">
      <span class="trusted-bar__label">Trusted by</span>
      <div class="trusted-bar__logos">
        <div class="trusted-bar__track" aria-hidden="true">
          <img src="<?php echo rip_asset('brand-logos/10.png'); ?>" alt="Client logo" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/6.png'); ?>"  alt="Client logo" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/8.png'); ?>"  alt="Client logo" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/9.png'); ?>"  alt="Client logo" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/11.png'); ?>" alt="Client logo" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/7.png'); ?>"  alt="Client logo" width="120" height="40" loading="lazy">
          <!-- duplicate for seamless loop -->
          <img src="<?php echo rip_asset('brand-logos/10.png'); ?>" alt="" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/6.png'); ?>"  alt="" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/8.png'); ?>"  alt="" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/9.png'); ?>"  alt="" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/11.png'); ?>" alt="" width="120" height="40" loading="lazy">
          <img src="<?php echo rip_asset('brand-logos/7.png'); ?>"  alt="" width="120" height="40" loading="lazy">
        </div>
      </div>
    </div>
  </section>

  <!-- ===== 4. PROMISE / HIGHLIGHT-SWEEP ===== -->
  <section class="promise" id="promise">
    <div class="promise__inner">
      <p class="promise__tag">[ why we're different ]</p>
      <h2 class="promise__statement" id="promiseText">
        Ranked International takes
        <span class="hl" data-icon="lock">one client per industry, per city</span>
        so we <span class="hl" data-icon="shield">never optimize your competitor against you</span>.
        <span class="hl" data-icon="bolt">No 12-month contracts.</span>
        Every report ends with one number:
        <span class="hl" data-icon="trending-up">keywords ranked.</span>
      </h2>
    </div>
  </section>

  <!-- ===== 6. SERVICES ROOF ===== -->
  <section class="services-roof" id="services" aria-label="All SEO and paid services under one roof">
    <div class="services-roof__inner">
      <h2>All Services Under One Roof</h2>
      <div class="services-roof__map">
        <svg class="services-roof__lines" viewBox="0 0 934 458" preserveAspectRatio="none" aria-hidden="true">
          <path d="M106 94 C106 154 156 175 220 175 H410 C455 175 467 199 467 229" />
          <path d="M467 94 V229" />
          <path d="M815 94 C815 154 765 175 701 175 H524 C480 175 467 199 467 229" />
          <path d="M106 373 C106 314 156 287 220 287 H410 C455 287 467 259 467 229" />
          <path d="M467 373 V229" />
          <path d="M815 373 C815 314 765 287 701 287 H524 C480 287 467 259 467 229" />
        </svg>

        <a href="#audit" class="services-roof__center">Get You Ranked</a>

        <div class="services-roof__chip services-roof__chip--local">
          <div class="services-roof__chip-head">
            <img src="<?php echo rip_asset('icons/edit_location_alt.svg'); ?>" alt="" loading="lazy">
            <span>Local SEO</span>
          </div>
          <p>Boost your visibility in local search results and map listings.</p>
        </div>
        <div class="services-roof__chip services-roof__chip--technical">
          <div class="services-roof__chip-head">
            <img src="<?php echo rip_asset('icons/my_location.svg'); ?>" alt="" loading="lazy">
            <span>Technical SEO</span>
          </div>
          <p>A strong technical foundation supports better rankings and user experience.</p>
        </div>
        <div class="services-roof__chip services-roof__chip--organic">
          <div class="services-roof__chip-head">
            <img src="<?php echo rip_asset('icons/all_inclusive.svg'); ?>" alt="" loading="lazy">
            <span>Organic SEO</span>
          </div>
          <p>On-page SEO lays the groundwork for long-term success.</p>
        </div>
        <div class="services-roof__chip services-roof__chip--link">
          <div class="services-roof__chip-head">
            <img src="<?php echo rip_asset('icons/anchor.svg'); ?>" alt="" loading="lazy">
            <span>Link Building</span>
          </div>
          <p>High-quality backlinks that improve your domain authority &amp; rankings.</p>
        </div>
        <div class="services-roof__chip services-roof__chip--ads">
          <div class="services-roof__chip-head">
            <img src="<?php echo rip_asset('icons/campaign.svg'); ?>" alt="" loading="lazy">
            <span>Google Ads</span>
          </div>
          <p>Campaign tailored to your goals - increase traffic or generate leads.</p>
        </div>
        <div class="services-roof__chip services-roof__chip--ppc">
          <div class="services-roof__chip-head">
            <img src="<?php echo rip_asset('icons/ads_click.svg'); ?>" alt="" loading="lazy">
            <span>PPC Management</span>
          </div>
          <p>Manage entire process across Google Ads, Microsoft Ads, and Meta Ads.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== 6. CASE STUDIES ===== -->
  <section class="case-studies" id="results">
    <div class="cs__inner">
      <div class="cs__header">
        <div class="cs__header-left">
          <p class="eyebrow"><span class="dot"></span>Client results</p>
          <h2 class="cs__title">Results Dallas businesses<br>actually talk about.</h2>
        </div>
        <a href="/case-studies/" class="cs__see-all">&#9658;&nbsp;See all results</a>
      </div>
      <div class="cs__layout">
        <div class="cs__center">
          <div class="cs__stage">
            <article class="cs__card" data-cs-idx="0">
              <span class="cs__card-kicker">01 &mdash; Bella Med Spa</span>
              <blockquote class="cs__quote">7.5&times; the organic traffic for a Dallas med spa.</blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client">Bella MedSpa &amp; Aesthetics &mdash; Dallas, TX</p>
                <a href="/case-studies/bella-med-spa/" class="cs__cta-link">&#9658;&nbsp;Read case study</a>
              </footer>
            </article>
            <article class="cs__card" data-cs-idx="1">
              <span class="cs__card-kicker">02 &mdash; Reyes Custom Millwork</span>
              <blockquote class="cs__quote">#1 for Dallas custom cabinets, with 6.7&times; the traffic.</blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client">Reyes Custom Millwork &mdash; Dallas, TX</p>
                <a href="/case-studies/reyes-custom-millwork/" class="cs__cta-link">&#9658;&nbsp;Read case study</a>
              </footer>
            </article>
            <article class="cs__card" data-cs-idx="2">
              <span class="cs__card-kicker">03 &mdash; DFW Flower Wall</span>
              <blockquote class="cs__quote">From zero organic traffic to DFW&rsquo;s #1 party-rental brand.</blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client">DFW Flower Wall &mdash; Dallas, TX</p>
                <a href="/case-studies/dfw-flower-wall/" class="cs__cta-link">&#9658;&nbsp;Read case study</a>
              </footer>
            </article>
            <article class="cs__card" data-cs-idx="3">
              <span class="cs__card-kicker">04 &mdash; Social Pro Photo Booth</span>
              <blockquote class="cs__quote">From one Dallas photo booth to #1 in 24 cities nationwide.</blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client">Social Pro Photo Booth &mdash; Dallas, TX</p>
                <a href="/case-studies/social-pro-photo-booth/" class="cs__cta-link">&#9658;&nbsp;Read case study</a>
              </footer>
            </article>
            <article class="cs__card" data-cs-idx="4">
              <span class="cs__card-kicker">05 &mdash; TX Artificial Turf &amp; Design</span>
              <blockquote class="cs__quote">Zero to Domain Authority 55 and #1 in Dallas turf.</blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client">TX Artificial Turf &amp; Design &mdash; Dallas-Fort Worth</p>
                <a href="/case-studies/turf-and-design/" class="cs__cta-link">&#9658;&nbsp;Read case study</a>
              </footer>
            </article>
            <article class="cs__card" data-cs-idx="5">
              <span class="cs__card-kicker">06 &mdash; Alexis Delivery Service</span>
              <blockquote class="cs__quote">Building a 25-city moving footprint, one page at a time.</blockquote>
              <footer class="cs__card-foot">
                <p class="cs__client">Alexis Delivery Service &mdash; Dallas-Fort Worth</p>
                <a href="/case-studies/alexis-delivery-service/" class="cs__cta-link">&#9658;&nbsp;Read case study</a>
              </footer>
            </article>
          </div>
          <div class="cs__controls">
            <button class="cs__arrow" data-cs-prev aria-label="Previous case study">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M11 4L6 9l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="cs__pips" aria-hidden="true">
              <span class="cs__pip"></span>
              <span class="cs__pip"></span>
              <span class="cs__pip"></span>
              <span class="cs__pip"></span>
              <span class="cs__pip"></span>
              <span class="cs__pip"></span>
            </div>
            <button class="cs__arrow" data-cs-next aria-label="Next case study">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
          </div>
        </div>
        <div class="cs__metrics">
          <div class="cs__metrics-panel is-active" data-cs-metrics="0">
            <div class="cs__metric-block">
              <p class="cs__metric-label">Monthly organic traffic</p>
              <p class="cs__metric-num">1,500</p>
              <p class="cs__metric-sub">up from 200 visitors/month</p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label">Traffic growth</p>
              <p class="cs__metric-num">7.5<span>&times;</span></p>
              <p class="cs__metric-sub">and still compounding</p>
            </div>
          </div>
          <div class="cs__metrics-panel" data-cs-metrics="1">
            <div class="cs__metric-block">
              <p class="cs__metric-label">Monthly organic traffic</p>
              <p class="cs__metric-num">234</p>
              <p class="cs__metric-sub">up from 35 &middot; 6.7&times; growth</p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label">Search position</p>
              <p class="cs__metric-num">#1</p>
              <p class="cs__metric-sub">for &ldquo;dallas custom cabinets&rdquo;</p>
            </div>
          </div>
          <div class="cs__metrics-panel" data-cs-metrics="2">
            <div class="cs__metric-block">
              <p class="cs__metric-label">Monthly organic traffic</p>
              <p class="cs__metric-num">1,297</p>
              <p class="cs__metric-sub">up from zero in 24 months</p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label">#1 rankings</p>
              <p class="cs__metric-num">20</p>
              <p class="cs__metric-sub">across DFW rental categories</p>
            </div>
          </div>
          <div class="cs__metrics-panel" data-cs-metrics="3">
            <div class="cs__metric-block">
              <p class="cs__metric-label">Monthly organic traffic</p>
              <p class="cs__metric-num">1,632</p>
              <p class="cs__metric-sub">up from 680 &middot; 2.4&times; in 24 months</p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label">#1 rankings</p>
              <p class="cs__metric-num">24</p>
              <p class="cs__metric-sub">across Dallas and 15+ U.S. cities</p>
            </div>
          </div>
          <div class="cs__metrics-panel" data-cs-metrics="4">
            <div class="cs__metric-block">
              <p class="cs__metric-label">Monthly organic traffic</p>
              <p class="cs__metric-num">221</p>
              <p class="cs__metric-sub">up from zero &middot; breakout after 18 months</p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label">Domain Authority</p>
              <p class="cs__metric-num">55</p>
              <p class="cs__metric-sub">with five #1 Google rankings</p>
            </div>
          </div>
          <div class="cs__metrics-panel" data-cs-metrics="5">
            <div class="cs__metric-block">
              <p class="cs__metric-label">City pages live</p>
              <p class="cs__metric-num">25<span>+</span></p>
              <p class="cs__metric-sub">across the DFW metro</p>
            </div>
            <div class="cs__metric-block">
              <p class="cs__metric-label">Best ranking</p>
              <p class="cs__metric-num">#3</p>
              <p class="cs__metric-sub">with eight keywords already in the top 20</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== 8. INDUSTRIES MARQUEE ===== -->
  <section class="industries" id="industries">
    <div class="section-head">
      <p class="eyebrow eyebrow--light"><span class="dot"></span>Built for your trade</p>
      <h2 class="section-title section-title--light">We rank the businesses Dallas searches for.</h2>
    </div>
    <div class="marquee" id="marquee">
      <div class="marquee__row" data-dir="1"></div>
      <div class="marquee__row" data-dir="-1"></div>
    </div>
  </section>

  <section class="google-reviews" id="reviews" aria-labelledby="reviews-title">
    <div class="google-reviews__inner">
      <header class="google-reviews__head">
        <div class="google-reviews__rating"><span class="google-reviews__g" aria-hidden="true">G</span><span>5 Star Rated on Google</span><span class="google-reviews__stars" aria-label="Five stars">★★★★★</span></div>
        <h2 id="reviews-title">Dallas businesses trust Ranked to <em>get them found.</em></h2>
        <p>Search growth is only useful when it brings the right customers through the door.</p>
      </header>
      <div class="google-reviews__viewport" data-review-viewport tabindex="0" aria-label="Client reviews">
        <div class="google-reviews__grid" data-review-grid>
          <article class="g-review"><div class="g-review__top"><span class="g-review__avatar">BM</span><div><h3>Bella MedSpa &amp; Aesthetics</h3><p>Dallas, TX</p></div><span class="g-review__mark" aria-hidden="true">G</span></div><div class="g-review__stars" aria-label="Five stars">★★★★★</div><blockquote>“Organic search has become a dependable way for the right patients to find us. We can finally see which pages and searches are moving the business.”</blockquote><a href="/case-studies/bella-med-spa/">View case study <span aria-hidden="true">→</span></a></article>
          <article class="g-review"><div class="g-review__top"><span class="g-review__avatar">RM</span><div><h3>Reyes Custom Millwork</h3><p>Dallas, TX</p></div><span class="g-review__mark" aria-hidden="true">G</span></div><div class="g-review__stars" aria-label="Five stars">★★★★★</div><blockquote>“We went from barely showing up to ranking at the top for the cabinet searches that matter in Dallas. The reporting is clear, and the growth has held.”</blockquote><a href="/case-studies/reyes-custom-millwork/">View case study <span aria-hidden="true">→</span></a></article>
          <article class="g-review"><div class="g-review__top"><span class="g-review__avatar">DF</span><div><h3>DFW Flower Wall</h3><p>Dallas, TX</p></div><span class="g-review__mark" aria-hidden="true">G</span></div><div class="g-review__stars" aria-label="Five stars">★★★★★</div><blockquote>“They built out every rental category properly and gave us visibility we never had before. Customers now find us for far more than just flower walls.”</blockquote><a href="/case-studies/dfw-flower-wall/">View case study <span aria-hidden="true">→</span></a></article>
          <article class="g-review"><div class="g-review__top"><span class="g-review__avatar">SP</span><div><h3>Social Pro Photo Booth</h3><p>Dallas, TX</p></div><span class="g-review__mark" aria-hidden="true">G</span></div><div class="g-review__stars" aria-label="Five stars">★★★★★</div><blockquote>“Ranked understood that we serve markets beyond Dallas and built a strategy around every city and product. Our nationwide visibility is on another level.”</blockquote><a href="/case-studies/social-pro-photo-booth/">View case study <span aria-hidden="true">→</span></a></article>
          <article class="g-review"><div class="g-review__top"><span class="g-review__avatar">TD</span><div><h3>TX Artificial Turf &amp; Design</h3><p>Dallas-Fort Worth</p></div><span class="g-review__mark" aria-hidden="true">G</span></div><div class="g-review__stars" aria-label="Five stars">★★★★★</div><blockquote>“The results took consistent work, but the authority and rankings kept building. We now show up for the Dallas turf searches we used to miss.”</blockquote><a href="/case-studies/turf-and-design/">View case study <span aria-hidden="true">→</span></a></article>
          <article class="g-review"><div class="g-review__top"><span class="g-review__avatar">AD</span><div><h3>Alexis Delivery Service</h3><p>Dallas-Fort Worth</p></div><span class="g-review__mark" aria-hidden="true">G</span></div><div class="g-review__stars" aria-label="Five stars">★★★★★</div><blockquote>“We are still early in the campaign, but the city pages are starting to rank and the roadmap is clear. We know what is being built and why.”</blockquote><a href="/case-studies/alexis-delivery-service/">View case study <span aria-hidden="true">→</span></a></article>
        </div>
      </div>
      <div class="google-reviews__controls"><button type="button" data-review-prev aria-label="Previous reviews">←</button><span aria-hidden="true">Swipe to see more</span><button type="button" data-review-next aria-label="Next reviews">→</button></div>
    </div>
  </section>

  <!-- ===== 9. PROCESS ===== -->
  <section class="process" id="process">
    <div class="process__head">
      <p class="eyebrow eyebrow--light"><span class="dot"></span>How it works</p>
    </div>
    <ol class="process__steps" id="processSteps">
      <li class="process__step">
        <div class="process__step-inner">
          <div class="process__icon-wrap">
            <span class="process__dot"></span>
            <span class="process__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg></span>
          </div>
          <h3 class="process__title">Free audit</h3>
          <div class="process__right">
            <p class="process__desc">I show you the 3 keywords you're losing to competitors right now.</p>
            <a href="#audit" class="process__btn">Get free audit <i data-lucide="arrow-right"></i></a>
          </div>
        </div>
      </li>
      <li class="process__step">
        <div class="process__step-inner">
          <div class="process__icon-wrap">
            <span class="process__dot"></span>
            <span class="process__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"></path></svg></span>
          </div>
          <h3 class="process__title">90-day plan</h3>
          <div class="process__right">
            <p class="process__desc">A focused plan for the searches that bring you paying customers.</p>
            <a href="#audit" class="process__btn">Get free audit <i data-lucide="arrow-right"></i></a>
          </div>
        </div>
      </li>
      <li class="process__step">
        <div class="process__step-inner">
          <div class="process__icon-wrap">
            <span class="process__dot"></span>
            <span class="process__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"></path><path d="M14 7h7v7"></path></svg></span>
          </div>
          <h3 class="process__title">Build &amp; rank</h3>
          <div class="process__right">
            <p class="process__desc">We do the work — technical, content, local, links — and get you up.</p>
            <a href="#audit" class="process__btn">Get free audit <i data-lucide="arrow-right"></i></a>
          </div>
        </div>
      </li>
      <li class="process__step">
        <div class="process__step-inner">
          <div class="process__icon-wrap">
            <span class="process__dot"></span>
            <span class="process__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"></line><line x1="12" x2="12" y1="20" y2="4"></line><line x1="6" x2="6" y1="20" y2="14"></line></svg></span>
          </div>
          <h3 class="process__title">Report leads booked</h3>
          <div class="process__right">
            <p class="process__desc">Every month, one number that matters: jobs in your calendar.</p>
            <a href="#audit" class="process__btn">Get free audit <i data-lucide="arrow-right"></i></a>
          </div>
        </div>
      </li>
    </ol>
    <a href="#audit" class="process__btn-mobile">Get free audit <i data-lucide="arrow-right"></i></a>
  </section>

  <!-- ===== 10. FAQ ===== -->
  <section class="faq" id="faq">
    <div class="faq__head">

      <h2>FAQ's: What Every Business Owner Should Know</h2>
      <p>Curious about how SEO works or what to expect? We've answered the most common questions to help you understand how Ranked International can support your digital growth.</p>
    </div>
    <div class="faq__list">
      <article class="faq__item is-open">
        <button class="faq__question" type="button" aria-expanded="true">
          <span>How long does SEO usually take?</span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="faq__answer">
          <p>Most local campaigns start showing directional movement in the first 60 to 90 days. Competitive markets can take longer, but you will know exactly what we are working on and why.</p>
        </div>
      </article>
      <article class="faq__item">
        <button class="faq__question" type="button" aria-expanded="false">
          <span>What is included in the free audit?</span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="faq__answer">
          <p>We look at your search visibility, website fundamentals, local presence, competitors, and the highest-value keywords you are missing today.</p>
        </div>
      </article>
      <article class="faq__item">
        <button class="faq__question" type="button" aria-expanded="false">
          <span>Do you work with competing businesses?</span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="faq__answer">
          <p>No. We take one client per industry, per city, so we are not optimizing your competitor against you.</p>
        </div>
      </article>
      <article class="faq__item">
        <button class="faq__question" type="button" aria-expanded="false">
          <span>Do I need SEO, PPC, or both?</span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </button>
        <div class="faq__answer">
          <p>It depends on your timeline and market. PPC can create faster lead flow, while SEO builds durable visibility. Many businesses benefit from using both together.</p>
        </div>
      </article>
    </div>
  </section>

</main>

<?php rip_render_audit_modal(); ?>
<?php get_footer(); ?>
