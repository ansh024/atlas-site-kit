<?php get_header(); ?>

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
          <input type="text" name="website" inputmode="url" autocomplete="url" placeholder="www.example.com" required>
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
<?php get_footer(); ?>
