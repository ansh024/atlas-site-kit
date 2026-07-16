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
<?php rip_render_audit_modal(); ?>
<?php get_footer(); ?>
