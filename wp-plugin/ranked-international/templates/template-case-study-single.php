<?php
/**
 * Single template for the `rip_case_study` post type.
 * All content comes from the "Case Study Details" ACF field group
 * (see acf-json/group_rip_case_study.json) — duplicate a Case Study post
 * and fill in the fields to publish a new one, no code required.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$client_name    = get_field( 'client_name' ) ?: get_the_title();
$industry_tag   = get_field( 'industry_tag' );
$live_url       = get_field( 'live_url' );
$hero_title     = get_field( 'hero_title' );
$hero_sub       = get_field( 'hero_sub' );
$chart_label    = get_field( 'chart_label' ) ?: 'Monthly organic traffic';
$chart_value    = get_field( 'chart_current_value' );
$chart_unit     = get_field( 'chart_current_unit' ) ?: 'visitors / month';
$chart_badge    = get_field( 'chart_badge' );
$chart_start    = get_field( 'chart_start_label' );
$chart_end      = get_field( 'chart_end_label' );
$strategy_title = get_field( 'strategy_title' );
$strategy_lede  = get_field( 'strategy_lede' );
$results_title  = get_field( 'results_title' );
$table_caption  = get_field( 'table_caption' ) ?: 'A sample of the keywords ranking today';
$table_source   = get_field( 'table_source' ) ?: 'Source: live rank tracking';
$related_ids    = array_filter( (array) ( get_field( 'related_case_studies' ) ?: array() ),
	function ( $rid ) { return get_post_status( $rid ) === 'publish'; } ); // hide drafts from related cards
$hub_url        = rip_url_for_template( 'templates/template-case-studies-hub.php', '/case-studies/' );

$chart_points = array();
if ( have_rows( 'chart_points' ) ) {
	while ( have_rows( 'chart_points' ) ) { the_row(); $chart_points[] = get_sub_field( 'value' ); }
}

?>
<?php get_header(); ?>

<!-- ===== HERO ===== -->
<section class="hero hero--case-study" id="hero">
  <div class="hero__bg" aria-hidden="true">
    <div class="hero__aurora"></div>
    <div class="hero__grain"></div>
  </div>
  <div class="hero__inner">
    <div class="hero__copy">
      <p class="csp-crumb">
        <a href="<?php echo esc_url( $hub_url ); ?>">Case Studies</a><span>/</span><em><?php echo esc_html( $client_name ); ?></em>
      </p>
      <div class="hero__text-block">
        <?php if ( $industry_tag ) : ?><p class="hero__eyebrow"><?php echo esc_html( $industry_tag ); ?></p><?php endif; ?>
        <h1 class="hero__title"><?php echo wp_kses_post( $hero_title ); ?></h1>
        <?php if ( $hero_sub ) : ?><p class="hero__sub"><?php echo esc_html( $hero_sub ); ?></p><?php endif; ?>
      </div>
      <div class="hero__bottom csp-hero-actions">
        <a href="#audit" class="btn btn--primary btn--lg">Get my free audit</a>
        <?php if ( $live_url ) : ?><a href="<?php echo esc_url( $live_url ); ?>" target="_blank" rel="noopener" class="csp-hero-link">View live site <i data-lucide="arrow-up-right"></i></a><?php endif; ?>
      </div>
      <?php if ( have_rows( 'hero_stats' ) ) : ?>
      <div class="csp-stats">
        <?php while ( have_rows( 'hero_stats' ) ) : the_row(); ?>
        <div class="csp-stat-row">
          <span class="csp-stat-row__label"><?php echo wp_kses_post( get_sub_field( 'label' ) ); ?></span>
          <span class="csp-stat-row__num"><?php echo wp_kses_post( get_sub_field( 'value' ) ); ?></span>
        </div>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>
    </div>

    <?php if ( $chart_value || $chart_points ) : ?>
    <div class="csp-chart-card">
      <div class="csp-chart-top">
        <span class="csp-chart-label"><?php echo esc_html( $chart_label ); ?></span>
        <div class="csp-chart-now">
          <strong><?php echo esc_html( $chart_value ); ?></strong>
          <span><?php echo esc_html( $chart_unit ); ?></span>
        </div>
      </div>
      <?php if ( $chart_badge ) : ?><span class="csp-chart-badge"><i data-lucide="trending-up" style="width:12px;height:12px"></i> <?php echo esc_html( $chart_badge ); ?></span><?php endif; ?>
      <?php echo rip_render_sparkline( $chart_points ); ?>
      <?php if ( $chart_start || $chart_end ) : ?>
      <div class="csp-chart-axis"><span><?php echo esc_html( $chart_start ); ?></span><span><?php echo esc_html( $chart_end ); ?></span></div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php if ( have_rows( 'context_pills' ) ) : ?>
<!-- ===== CONTEXT STRIP ===== -->
<section class="csp-context">
  <div class="csp-context__inner">
    <?php while ( have_rows( 'context_pills' ) ) : the_row(); ?>
    <span class="pill"><i data-lucide="<?php echo esc_attr( get_sub_field( 'icon' ) ?: 'tag' ); ?>"></i><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
    <?php endwhile; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( $strategy_lede || have_rows( 'strategy_cards' ) ) : ?>
<!-- ===== STRATEGY ===== -->
<section class="csp-strategy">
  <div class="csp-strategy__inner">
    <div class="section-head">
      <p class="eyebrow"><span class="dot"></span>What we did</p>
      <?php if ( $strategy_title ) : ?><h2 class="section-title"><?php echo wp_kses_post( $strategy_title ); ?></h2><?php endif; ?>
    </div>
    <?php if ( $strategy_lede ) : ?><p class="csp-strategy__lede"><?php echo wp_kses_post( $strategy_lede ); ?></p><?php endif; ?>

    <?php if ( have_rows( 'strategy_cards' ) ) : ?>
    <div class="csp-strategy__grid">
      <?php $i = 0; while ( have_rows( 'strategy_cards' ) ) : the_row(); $i++; ?>
      <div class="csp-strategy__card">
        <p class="csp-strategy__num"><?php echo esc_html( sprintf( '%02d', $i ) ); ?></p>
        <h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
        <p><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
      </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( have_rows( 'results_metrics' ) ) : ?>
<!-- ===== RESULTS ===== -->
<section class="csp-results">
  <div class="csp-results__inner">
    <div class="section-head">
      <p class="eyebrow"><span class="dot"></span>The results</p>
      <?php if ( $results_title ) : ?><h2 class="section-title"><?php echo wp_kses_post( $results_title ); ?></h2><?php endif; ?>
    </div>
    <?php
    $metric_rows = get_field( 'results_metrics' );
    $count_class = count( $metric_rows ) === 2 ? ' n2' : ( count( $metric_rows ) === 3 ? ' n3' : '' );
    ?>
    <div class="csp-metric-grid<?php echo esc_attr( $count_class ); ?>">
      <?php while ( have_rows( 'results_metrics' ) ) : the_row(); ?>
      <div class="csp-metric">
        <p class="csp-metric-label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></p>
        <p class="csp-metric-num"><?php echo wp_kses_post( get_sub_field( 'value' ) ); ?></p>
        <p class="csp-metric-sub"><?php echo esc_html( get_sub_field( 'sub' ) ); ?></p>
      </div>
      <?php endwhile; ?>
    </div>

    <?php if ( have_rows( 'keyword_rows' ) ) : ?>
    <div class="csp-table-wrap">
      <div class="csp-table-head">
        <h3><?php echo esc_html( $table_caption ); ?></h3>
        <span><?php echo esc_html( $table_source ); ?></span>
      </div>
      <table class="csp-table">
        <thead><tr><th>Keyword</th><th>Position</th><th>Search volume</th></tr></thead>
        <tbody>
          <?php while ( have_rows( 'keyword_rows' ) ) : the_row(); ?>
          <tr>
            <td><?php echo esc_html( get_sub_field( 'keyword' ) ); ?></td>
            <td class="csp-pos"><?php echo esc_html( get_sub_field( 'position' ) ); ?></td>
            <td><?php echo esc_html( get_sub_field( 'volume' ) ?: '—' ); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( ! empty( $related_ids ) ) : ?>
<!-- ===== RELATED CASE STUDIES ===== -->
<section class="csp-related">
  <div class="csp-related__inner">
    <div class="section-head">
      <p class="eyebrow"><span class="dot"></span>More results</p>
      <h2 class="section-title">Other Dallas businesses <em>winning</em> on Google.</h2>
    </div>
    <div class="csp-related__grid">
      <?php foreach ( $related_ids as $rid ) :
        $rlogo = get_field( 'client_logo', $rid );
        $rname = get_field( 'client_name', $rid ) ?: get_the_title( $rid );
        $rtag  = get_field( 'industry_tag', $rid );
      ?>
      <a class="csp-related__card" href="<?php echo esc_url( get_permalink( $rid ) ); ?>">
        <?php if ( $rlogo ) : ?><img class="csp-related__logo" src="<?php echo esc_url( $rlogo ); ?>" alt="<?php echo esc_attr( $rname ); ?> logo"><?php endif; ?>
        <h3><?php echo esc_html( $rname ); ?></h3>
        <p><?php echo esc_html( $rtag ); ?></p>
        <span class="csp-related__stat">See the results <i data-lucide="arrow-right" style="width:14px;height:14px"></i></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== FINAL CTA ===== -->
<section class="cta" id="cta-final">
  <div class="cta__inner">
    <div class="cta__copy">
      <p class="cta__title">Want results like this <em>for your business</em>?</p>
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
        <label class="audit-field"><span>Name</span><input type="text" name="name" autocomplete="name" required></label>
        <label class="audit-field"><span>Email</span><input type="email" name="email" autocomplete="email" required></label>
        <div class="audit-modal__actions"><button type="button" class="btn btn--primary btn--block" data-audit-next>Continue</button></div>
      </div>
      <div class="audit-modal__step" data-audit-step="2">
        <p class="audit-modal__eyebrow">Step 2 of 2</p>
        <h2>A bit about your business</h2>
        <label class="audit-field"><span>Website</span><input type="text" name="website" inputmode="url" autocomplete="url" placeholder="www.example.com" required></label>
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
<?php get_footer(); ?>
