<?php
/**
 * Plugin Name: Ranked International Pages
 * Description: Adds the Ranked International marketing pages (home, industry landers, case studies) as selectable Page Templates that work on top of any active theme.
 * Version: 1.1.0
 * Author: Ranked International
 * Text Domain: ranked-international
 * GitHub Plugin URI: ansh024/atlas-site-kit
 * Primary Branch: plugin-deploy
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RIP_VERSION', '1.1.0' );
define( 'RIP_DIR', plugin_dir_path( __FILE__ ) );
define( 'RIP_URL', plugin_dir_url( __FILE__ ) );

require_once RIP_DIR . 'includes/cpt.php';
require_once RIP_DIR . 'includes/seed.php';
require_once RIP_DIR . 'includes/seo.php';
require_once RIP_DIR . 'includes/leads.php';

const RIP_AUDIT_WPFORMS_ID = 2845;

function rip_wpforms_audit_available() {
	return shortcode_exists( 'wpforms' )
		&& get_post_type( RIP_AUDIT_WPFORMS_ID ) === 'wpforms'
		&& get_post_status( RIP_AUDIT_WPFORMS_ID ) !== 'trash';
}

function rip_wpforms_audit_html() {
	static $html = null;
	if ( $html !== null ) return $html;
	if ( ! rip_wpforms_audit_available() ) {
		$html = '<p class="audit-modal__unavailable">The audit form is temporarily unavailable. Please use the Contact page or call us.</p>';
		return $html;
	}
	$html = do_shortcode( '[wpforms id="2845" title="false"]' );
	return $html;
}

function rip_render_audit_modal() {
	?>
	<div class="audit-modal" id="auditModal" aria-hidden="true">
		<div class="audit-modal__backdrop" data-audit-close></div>
		<div class="audit-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="auditTitle">
			<button class="audit-modal__close" type="button" data-audit-close aria-label="Close audit form"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
			<h2 class="screen-reader-text" id="auditTitle">Request a free audit</h2>
			<div class="audit-modal__wpforms"><?php echo rip_wpforms_audit_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
	<?php
}

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) || rip_wpforms_audit_available() ) return;
	echo '<div class="notice notice-error"><p><strong>Ranked audit modal:</strong> WPForms form 2845 is unavailable. Activate WPForms and publish form 2845.</p></div>';
} );

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
		'templates/template-turf-tree-service.php' => 'Ranked Intl: Turf & Tree Service',
	);
}

/**
 * Return editable city-page copy while keeping the shared home layout as the
 * single source of truth. Home requests always receive the location-neutral
 * fallback; City Page requests receive their ACF value when one is present.
 */
function rip_home_copy( $field, $neutral_fallback ) {
	$is_city_page = is_singular( 'rip_city' );
	if ( ! $is_city_page || ! function_exists( 'get_field' ) ) {
		return $neutral_fallback;
	}
	$value = get_field( $field, get_queried_object_id() );
	return $value !== null && $value !== '' ? $value : $neutral_fallback;
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
	if ( is_singular( 'rip_city' ) ) {
		return RIP_DIR . 'templates/template-city.php';
	}
	if ( is_singular( 'rip_industry' ) ) {
		return RIP_DIR . 'templates/template-industry-page.php';
	}
	if ( is_singular( 'rip_case_study' ) ) {
		return RIP_DIR . 'templates/template-case-study-single.php';
	}
	if ( is_singular( 'rip_service' ) ) {
		return RIP_DIR . 'templates/template-service.php';
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
	if ( is_singular( array( 'rip_city', 'rip_industry', 'rip_case_study', 'rip_service' ) ) ) return true;
	if ( ! is_page() ) return false;
	$slug = get_page_template_slug();
	return $slug && array_key_exists( $slug, rip_templates() );
}

/**
 * Keep WordPress in charge of the document, header and footer while giving
 * service-page CSS a stable namespace inside the active theme shell.
 */
add_filter( 'body_class', 'rip_service_body_classes' );
function rip_service_body_classes( $classes ) {
	if ( ! is_singular( 'rip_service' ) ) return $classes;

	$classes[] = 'rip-service-page';
	$classes[] = 'rip-service-template';
	$classes[] = 'evidence-' . sanitize_html_class( get_field( 'evidence_type' ) ?: 'map' );
	return array_unique( $classes );
}

/**
 * Only load our CSS/JS on pages actually using one of our templates —
 * keeps the rest of the site (and the active theme) untouched.
 */
/*
 * The active Outgrid theme has broad heading and layout selectors. Load the
 * plugin's isolated page layer after the theme so service-page rules retain
 * ownership of the service content without touching theme chrome.
 */
add_action( 'wp_enqueue_scripts', 'rip_enqueue_assets', 100 );
function rip_enqueue_assets() {
	if ( ! rip_is_our_template() ) return;

	wp_enqueue_style( 'rip-styles', RIP_URL . 'assets/css/styles.min.css', array(), RIP_VERSION );
	wp_enqueue_style( 'rip-fonts', 'https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap', array(), null );
	wp_enqueue_style( 'rip-page-fixes', RIP_URL . 'assets/css/page-fixes.css', array( 'rip-styles' ), RIP_VERSION );

	$is_case_study = is_singular( 'rip_case_study' ) || get_page_template_slug() === 'templates/template-case-studies-hub.php';
	$is_service = is_singular( 'rip_service' );
	$is_turf_tree = get_page_template_slug() === 'templates/template-turf-tree-service.php';
	if ( $is_case_study ) {
		wp_enqueue_style( 'rip-case-study', RIP_URL . 'assets/css/case-study.css', array( 'rip-styles' ), RIP_VERSION );
	}
	if ( $is_service ) {
		wp_enqueue_style( 'rip-service', RIP_URL . 'assets/css/service.css', array( 'rip-styles' ), RIP_VERSION );
	}
	if ( $is_turf_tree ) {
		wp_enqueue_style( 'rip-turf-tree', RIP_URL . 'assets/css/trade-landing.css', array( 'rip-styles', 'rip-page-fixes' ), RIP_VERSION );
	}

	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '3.12.5', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );
	wp_enqueue_script( 'lucide', 'https://unpkg.com/lucide@1.23.0/dist/umd/lucide.min.js', array(), '1.23.0', true );

	wp_enqueue_script( 'rip-main', RIP_URL . 'assets/js/main.js', array( 'gsap', 'gsap-scrolltrigger', 'lucide' ), RIP_VERSION, true );

	if ( $is_case_study ) {
		wp_enqueue_script( 'rip-case-study', RIP_URL . 'assets/js/case-study.js', array( 'gsap', 'gsap-scrolltrigger' ), RIP_VERSION, true );
	}
	if ( $is_service ) {
		wp_enqueue_script( 'rip-service', RIP_URL . 'assets/js/service.js', array( 'gsap', 'gsap-scrolltrigger', 'rip-main' ), RIP_VERSION, true );
	}
	if ( $is_turf_tree ) {
		wp_enqueue_script( 'rip-turf-tree', RIP_URL . 'assets/js/trade-landing.js', array( 'gsap', 'gsap-scrolltrigger', 'rip-main' ), RIP_VERSION, true );
	}

	// Render once before styles/scripts print so WPForms can enqueue its assets.
	rip_wpforms_audit_html();
}

/**
 * UiCore enables a site-wide blue cursor follower from its generated global
 * assets. Keep the native system cursor and hide only that decorative layer.
 * This lives outside the generated theme CSS so theme rebuilds cannot undo it.
 */
add_action( 'wp_head', 'rip_disable_uicore_custom_cursor', 100 );
function rip_disable_uicore_custom_cursor() {
	if ( is_admin() ) return;
	echo '<style id="rip-disable-uicore-cursor">.ui-cursor{display:none!important}</style>';
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
