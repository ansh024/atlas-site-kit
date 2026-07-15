<?php
/**
 * Plugin Name: Ranked International Pages
 * Description: Adds the Ranked International marketing pages (home, industry landers, case studies) as selectable Page Templates that work on top of any active theme.
 * Version: 1.0.0
 * Author: Ranked International
 * Text Domain: ranked-international
 * GitHub Plugin URI: ansh024/atlas-site-kit
 * Primary Branch: plugin-deploy
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RIP_VERSION', '1.0.0' );
define( 'RIP_DIR', plugin_dir_path( __FILE__ ) );
define( 'RIP_URL', plugin_dir_url( __FILE__ ) );

require_once RIP_DIR . 'includes/cpt.php';
require_once RIP_DIR . 'includes/seed.php';

/**
 * Map of template file => label shown in the Page Attributes dropdown.
 * Only the two hand-built pages live here — Industry Pages and Case Studies
 * are their own Custom Post Types (see includes/cpt.php) so the client can
 * duplicate/create them from wp-admin without touching a template picker.
 */
function rip_templates() {
	return array(
		'templates/template-home.php'            => 'Ranked Intl: Home',
		'templates/template-case-studies-hub.php' => 'Ranked Intl: Case Studies (Hub)',
	);
}

/**
 * Make our templates selectable under Page Attributes on any theme.
 */
add_filter( 'theme_page_templates', 'rip_add_page_templates' );
function rip_add_page_templates( $templates ) {
	return array_merge( $templates, rip_templates() );
}

/**
 * When a Page has one of our templates selected, serve our file instead of the theme's page.php.
 */
add_filter( 'template_include', 'rip_load_page_template' );
function rip_load_page_template( $template ) {
	if ( is_singular( 'rip_industry' ) ) {
		return RIP_DIR . 'templates/template-industry-page.php';
	}
	if ( is_singular( 'rip_case_study' ) ) {
		return RIP_DIR . 'templates/template-case-study-single.php';
	}

	if ( ! is_page() ) return $template;

	$slug = get_page_template_slug();
	if ( $slug && array_key_exists( $slug, rip_templates() ) ) {
		$file = RIP_DIR . $slug;
		if ( file_exists( $file ) ) return $file;
	}
	return $template;
}

/**
 * True when the current request will render one of our templates —
 * a Page using one of our Page Templates, or a singular Industry Page /
 * Case Study post.
 */
function rip_is_our_template() {
	if ( is_singular( array( 'rip_industry', 'rip_case_study' ) ) ) return true;
	if ( ! is_page() ) return false;
	$slug = get_page_template_slug();
	return $slug && array_key_exists( $slug, rip_templates() );
}

/**
 * Only load our CSS/JS on pages actually using one of our templates —
 * keeps the rest of the site (and the active theme) untouched.
 */
add_action( 'wp_enqueue_scripts', 'rip_enqueue_assets' );
function rip_enqueue_assets() {
	if ( ! rip_is_our_template() ) return;

	wp_enqueue_style( 'rip-styles', RIP_URL . 'assets/css/styles.min.css', array(), RIP_VERSION );

	$is_case_study = is_singular( 'rip_case_study' ) || get_page_template_slug() === 'templates/template-case-studies-hub.php';
	if ( $is_case_study ) {
		wp_enqueue_style( 'rip-case-study', RIP_URL . 'assets/css/case-study.css', array( 'rip-styles' ), RIP_VERSION );
	}

	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '3.12.5', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );
	wp_enqueue_script( 'lucide', 'https://unpkg.com/lucide@1.23.0/dist/umd/lucide.min.js', array(), '1.23.0', true );

	wp_enqueue_script( 'rip-main', RIP_URL . 'assets/js/main.js', array( 'gsap', 'gsap-scrolltrigger', 'lucide' ), RIP_VERSION, true );
	wp_localize_script( 'rip-main', 'RankdWP', array(
		'assetsUrl' => RIP_URL . 'assets/images/',
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'nonce'     => wp_create_nonce( 'rip_audit_lead' ),
	) );

	if ( $is_case_study ) {
		wp_enqueue_script( 'rip-case-study', RIP_URL . 'assets/js/case-study.js', array( 'gsap', 'gsap-scrolltrigger' ), RIP_VERSION, true );
	}
}

/**
 * Our templates print their own <title> and canonical in the template file.
 * WordPress core (via the theme's title-tag support) and SEO plugins (Yoast,
 * RankMath, AIOSEO) would otherwise inject a second <title>/canonical through
 * wp_head(), giving Google duplicate/conflicting tags. On our templates only,
 * suppress those duplicates; everywhere else the site behaves as before.
 */
add_action( 'template_redirect', 'rip_dedupe_head_tags' );
function rip_dedupe_head_tags() {
	if ( ! rip_is_our_template() ) return;

	// WP core title tag (printed when the theme declares title-tag support)
	remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	// WP core canonical on singular views
	remove_action( 'wp_head', 'rel_canonical' );

	// Yoast SEO (14+): remove the presenters for the tags our templates
	// print themselves. Removing the presenter is the reliable way — filters
	// like wpseo_title returning false can still emit an EMPTY <title>.
	// Yoast's other output (robots meta, schema, og:*) is left intact.
	add_filter( 'wpseo_frontend_presenters', 'rip_strip_yoast_presenters' );
	add_filter( 'wpseo_canonical', '__return_false' );

	// RankMath: same idea, via its documented disable filters
	add_filter( 'rank_math/frontend/canonical', '__return_false' );
	add_filter( 'rank_math/frontend/title', '__return_false' );
	add_filter( 'rank_math/frontend/description', '__return_false' );
}

function rip_strip_yoast_presenters( $presenters ) {
	return array_filter( $presenters, function ( $presenter ) {
		return ! preg_match(
			'/\\\\(Title|Meta_Description|Canonical)_Presenter$/',
			get_class( $presenter )
		);
	} );
}

/**
 * Resolve the front-end URL for one of our templates, if a Page has it assigned.
 * Falls back to a hardcoded path so links still work before the client sets pages up.
 */
function rip_url_for_template( $template_slug, $fallback_path ) {
	$pages = get_posts( array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => $template_slug,
		'post_status'    => 'publish',
	) );
	if ( ! empty( $pages ) ) return get_permalink( $pages[0] );
	return home_url( $fallback_path );
}

/**
 * Output the URL for one of our plugin's bundled image/video assets.
 */
function rip_asset( $path ) {
	return esc_url( RIP_URL . 'assets/images/' . ltrim( $path, '/' ) );
}

/**
 * Renders the case-study traffic chart (polygon fill + polyline + end dot)
 * from a plain array of numbers, matching the original hand-tuned SVG's
 * markup/classes exactly — so client-entered numbers still get a chart
 * instead of needing hand-plotted coordinates.
 */
function rip_render_sparkline( $values ) {
	$values = array_values( array_filter( $values, function ( $v ) { return $v !== '' && $v !== null; } ) );
	$n = count( $values );
	if ( $n < 2 ) return '';

	$min = min( $values );
	$max = max( $values );
	$range = ( $max - $min ) ?: 1;
	$top = 10; $bottom = 140; // matches original vertical padding inside the 380x160 viewBox

	$points = array();
	foreach ( $values as $i => $v ) {
		$x = round( $i * ( 380 / ( $n - 1 ) ), 1 );
		$y = round( $bottom - ( ( $v - $min ) / $range ) * ( $bottom - $top ), 1 );
		$points[] = "$x,$y";
	}
	$line = implode( ' ', $points );
	$fill = $line . ' 380,160 0,160';
	list( $last_x, $last_y ) = explode( ',', end( $points ) );

	ob_start(); ?>
<svg class="csp-chart-svg" viewBox="0 0 380 160" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Line chart of monthly organic traffic over time">
  <defs>
    <linearGradient id="cspGradient" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#caff00" stop-opacity=".28"/>
      <stop offset="100%" stop-color="#caff00" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <polygon class="csp-fill" points="<?php echo esc_attr( $fill ); ?>"/>
  <polyline class="csp-line" points="<?php echo esc_attr( $line ); ?>"/>
  <circle class="csp-dot" cx="<?php echo esc_attr( $last_x ); ?>" cy="<?php echo esc_attr( $last_y ); ?>" r="4"/>
</svg>
	<?php
	return ob_get_clean();
}

/**
 * Audit form lead handler (admin-ajax.php?action=rankd_audit_lead).
 * Emails the lead to the configured address and fires a do_action() hook
 * so a real CRM webhook can be wired up later without touching this file.
 */
add_action( 'wp_ajax_rankd_audit_lead', 'rip_handle_audit_lead' );
add_action( 'wp_ajax_nopriv_rankd_audit_lead', 'rip_handle_audit_lead' );
function rip_handle_audit_lead() {
	check_ajax_referer( 'rip_audit_lead', 'nonce' );

	$lead = array(
		'name'     => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ),
		'email'    => sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ),
		'website'  => sanitize_text_field( wp_unslash( $_POST['website'] ?? '' ) ),
		'service'  => sanitize_text_field( wp_unslash( $_POST['service'] ?? '' ) ),
		'page_url' => esc_url_raw( wp_unslash( $_POST['page_url'] ?? '' ) ),
	);

	$to      = apply_filters( 'rip_audit_lead_recipient', get_option( 'admin_email' ) );
	$subject = 'New free SEO audit request — ' . $lead['name'];
	$body    = "Name: {$lead['name']}\nEmail: {$lead['email']}\nWebsite: {$lead['website']}\nService: {$lead['service']}\nSubmitted from: {$lead['page_url']}";
	wp_mail( $to, $subject, $body );

	/**
	 * Fires after a lead is captured — hook a CRM webhook here without editing this plugin.
	 * do_action( 'rip_audit_lead_captured', array $lead )
	 */
	do_action( 'rip_audit_lead_captured', $lead );

	wp_send_json_success();
}
