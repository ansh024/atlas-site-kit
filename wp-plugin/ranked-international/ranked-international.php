<?php
/**
 * Plugin Name: Ranked International Pages
 * Description: Adds the Ranked International marketing pages (home, industry landers, case studies) as selectable Page Templates that work on top of any active theme.
 * Version: 1.2.0
 * Author: Ranked International
 * Text Domain: ranked-international
 * GitHub Plugin URI: ansh024/atlas-site-kit
 * Primary Branch: plugin-deploy
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RIP_VERSION', '1.2.0' );
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

/**
 * GHL form ID per campaign. Each campaign needs its own GHL form because the
 * post-submit redirect (which thank-you page the lead lands on) is configured
 * inside GHL, not here. Duplicate the form in GHL, point its redirect at the
 * campaign's thank-you path, then drop the new form ID in below.
 */
function rip_ghl_form_id( $campaign = 'default' ) {
	$forms = array(
		'default' => 'NnlAud8uVZoK09OlAAFj',
		// Redirect this one to /turf-thank-you/ inside GHL.
		'turf'    => 'NnlAud8uVZoK09OlAAFj',
	);
	return $forms[ $campaign ] ?? $forms['default'];
}

/** Render the campaign-specific GHL audit form without changing other pages. */
function rip_render_ghl_audit_modal( $campaign = 'default' ) {
	$form_id = rip_ghl_form_id( $campaign );
	?>
	<div class="audit-modal audit-modal--ghl" id="auditModal" aria-hidden="true">
		<div class="audit-modal__backdrop" data-audit-close></div>
		<div class="audit-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="auditTitle">
			<button class="audit-modal__close" type="button" data-audit-close aria-label="Close audit form"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
			<div class="ghl-modal__layout">
				<aside class="ghl-modal__value" aria-labelledby="auditTitle">
					<h2 id="auditTitle">Get a clearer path to more leads.</h2>
					<p class="ghl-modal__intro">See the search opportunities most likely to bring in more calls.</p>
					<ul class="ghl-modal__benefits">
						<li>Clear local SEO opportunities</li>
						<li>A practical plan for more calls</li>
					</ul>
				</aside>
				<div class="ghl-modal__form-panel">
					<iframe class="ghl-audit-form" data-ghl-src="https://api.leadconnectorhq.com/widget/form/<?php echo esc_attr( $form_id ); ?>" id="inline-<?php echo esc_attr( $form_id ); ?>" data-layout="{'id':'INLINE'}" data-trigger-type="alwaysShow" data-activation-type="alwaysActivated" data-deactivation-type="neverDeactivate" data-form-name="Meta Form" data-height="1000" data-layout-iframe-id="inline-<?php echo esc_attr( $form_id ); ?>" data-form-id="<?php echo esc_attr( $form_id ); ?>" title="Request your free SEO audit" loading="lazy"></iframe>
				</div>
			</div>
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
 * Only one-off pages live here. Reusable City, Industry, Service, and Case
 * Study pages are Custom Post Types (see includes/cpt.php), so editors can
 * duplicate or create them without touching a template picker.
 */
function rip_templates() {
	return array(
		'templates/template-home.php'            => 'Ranked Intl: Home',
		'templates/template-case-studies-hub.php' => 'Ranked Intl: Case Studies (Hub)',
		'templates/template-turf-tree-service.php' => 'Ranked Intl: Turf & Tree Service',
	);
}

/**
 * Campaign thank-you pages are plugin routes, so they need no database page.
 * Each campaign gets its own path so Meta can attribute leads per campaign
 * (URL-based custom conversions) without the events clashing.
 */
function rip_thank_you_routes() {
	return array(
		'/seo-audit-thank-you' => 'audit',
		'/turf-thank-you'      => 'turf',
	);
}

/** True on the Turf & Tree paid-traffic landing page. */
function rip_is_turf_tree_landing() {
	return is_page() && get_page_template_slug() === 'templates/template-turf-tree-service.php';
}

/**
 * The campaign markup changes more often than static assets, so browsers and
 * CDNs must revalidate this HTML instead of keeping an old modal for weeks.
 */
add_filter( 'wp_headers', function ( $headers ) {
	if ( ! rip_is_turf_tree_landing() ) return $headers;

	$headers['Cache-Control']                 = 'no-cache, no-store, must-revalidate, max-age=0';
	$headers['Cloudflare-CDN-Cache-Control'] = 'no-store';
	$headers['CDN-Cache-Control']            = 'no-store';
	$headers['Expires']                      = 'Wed, 11 Jan 1984 05:00:00 GMT';
	return $headers;
}, PHP_INT_MAX );

// Some production cache plugins replace Cache-Control during send_headers,
// after wp_headers has already run. Reassert the campaign policy at the last
// WordPress header hook so the public response cannot advertise a month-long
// browser TTL for page HTML.
add_action( 'send_headers', function () {
	if ( ! rip_is_turf_tree_landing() || headers_sent() ) return;

	header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0', true );
	header( 'Cloudflare-CDN-Cache-Control: no-store', true );
	header( 'CDN-Cache-Control: no-store', true );
	header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT', true );
}, PHP_INT_MAX );

/** Slug of the thank-you campaign for this request, or false when not one. */
function rip_thank_you_route() {
	if ( is_admin() ) return false;
	$path = untrailingslashit( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
	$routes = rip_thank_you_routes();
	return $routes[ $path ] ?? false;
}

function rip_is_audit_thank_you() {
	return rip_thank_you_route() !== false;
}

add_filter( 'redirect_canonical', function ( $redirect_url ) {
	return rip_is_audit_thank_you() ? false : $redirect_url;
} );

/**
 * These routes have no database page, so the main query finds nothing and
 * flags the request as a 404. status_header( 200 ) corrects the HTTP status
 * but not the query, which is what everything downstream reads: the theme
 * built a "Page not found" title, body_class printed error404, and Yoast
 * treated the page as missing. Clear the flag so the request is an ordinary
 * 200 to the rest of WordPress.
 */
add_action( 'template_redirect', function () {
	if ( ! rip_is_audit_thank_you() ) return;
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
}, 0 );

/**
 * Yoast also filters pre_get_document_title (priority 15), so this has to run
 * after it to win.
 */
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( ! rip_is_audit_thank_you() ) return $title;
	return 'Thank You | ' . get_bloginfo( 'name' );
}, 99 );

/** Campaign thank-you pages must never be indexed. */
add_filter( 'wpseo_robots', function ( $robots ) {
	return rip_is_audit_thank_you() ? 'noindex, nofollow' : $robots;
} );

add_action( 'wp_head', function () {
	if ( ! rip_is_audit_thank_you() || defined( 'WPSEO_VERSION' ) ) return;
	echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
}, 1 );

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
	if ( rip_is_audit_thank_you() ) {
		return RIP_DIR . 'templates/template-audit-thank-you.php';
	}
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
 * a Page using one of our Page Templates, or one of our reusable post types.
 */
function rip_is_our_template() {
	if ( rip_is_audit_thank_you() ) return true;
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
	$thank_you = rip_thank_you_route();
	if ( $thank_you ) {
		$classes[] = 'rip-audit-thank-you';
		$classes[] = 'rip-thank-you--' . sanitize_html_class( $thank_you );
		return array_unique( $classes );
	}
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
	$is_audit_thank_you = rip_is_audit_thank_you();
	if ( $is_case_study ) {
		wp_enqueue_style( 'rip-case-study', RIP_URL . 'assets/css/case-study.css', array( 'rip-styles' ), RIP_VERSION );
	}
	if ( $is_service ) {
		wp_enqueue_style( 'rip-service', RIP_URL . 'assets/css/service.css', array( 'rip-styles' ), RIP_VERSION );
	}
	if ( $is_turf_tree ) {
		wp_enqueue_style( 'rip-turf-tree', RIP_URL . 'assets/css/trade-landing.css', array( 'rip-styles', 'rip-page-fixes' ), RIP_VERSION );
	}
	if ( $is_audit_thank_you ) {
		wp_enqueue_style( 'rip-audit-thank-you', RIP_URL . 'assets/css/thank-you.css', array( 'rip-styles', 'rip-page-fixes' ), RIP_VERSION );
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
		wp_enqueue_script( 'rip-ghl-form-embed', 'https://link.msgsndr.com/js/form_embed.js', array(), null, true );
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
 * The theme's Elementor footer template ends every page with a "Schedule Your
 * Free Strategy Call" band plus the full site nav. On the paid-traffic landing
 * page that competes with the audit CTA and leaks clicks off the page, so drop
 * the whole <footer> element there. The theme still owns the closing document
 * markup, scripts, and back-to-top — only the footer element itself goes.
 */
add_action( 'get_footer', function () {
	if ( rip_is_turf_tree_landing() ) ob_start();
} );

add_action( 'wp_footer', function () {
	if ( ! rip_is_turf_tree_landing() || ! ob_get_level() ) return;
	$footer = ob_get_clean();

	$cleaned = preg_replace( '#<footer\b[^>]*id=["\']uicore-tb-footer["\'][^>]*>.*?</footer>#is', '', $footer );

	echo $cleaned === null ? $footer : $cleaned; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}, 0 );

/** Meta Lead event is intentionally limited to the post-submission thank-you route. */
add_action( 'wp_head', function () {
	if ( ! rip_is_audit_thank_you() ) return;
	?>
	<!-- Meta Pixel Code -->
	<script id="rip-meta-lead">
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init','1686472339304024');
	fbq('track','Lead');
	</script>
	<!-- End Meta Pixel Code -->
	<?php
}, 20 );

/**
 * A second Meta Pixel `Lead` snippet was pasted into WordPress by hand (it also
 * has a mismatched `PageView` noscript fallback), so every submission counted
 * as two conversions. The snippet above is the single source of truth for the
 * Lead event, so drop any other Lead-firing pixel block from <head>.
 *
 * This is a safety net, not the real fix: delete the pasted snippet in WP admin
 * (header-scripts plugin / theme or Elementor custom code) and this becomes a
 * no-op. Deliberately narrow — it only touches thank-you routes, only removes
 * blocks that fire `Lead`, and leaves the site-wide `PageView` pixel alone.
 */
add_action( 'wp_head', function () {
	if ( rip_is_audit_thank_you() ) ob_start();
}, 0 );

add_action( 'wp_head', function () {
	if ( ! rip_is_audit_thank_you() || ! ob_get_level() ) return;
	$head = ob_get_clean();

	$cleaned = preg_replace_callback(
		'#<!--\s*Meta Pixel Code\s*-->.*?<!--\s*End Meta Pixel Code\s*-->#is',
		function ( $match ) {
			$block = $match[0];
			// Keep our own block, and any block that does not fire Lead.
			if ( strpos( $block, 'rip-meta-lead' ) !== false ) return $block;
			if ( ! preg_match( '#fbq\(\s*[\'"]track[\'"]\s*,\s*[\'"]Lead[\'"]#i', $block ) ) return $block;
			return '';
		},
		$head
	);

	echo $cleaned === null ? $head : $cleaned; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}, 999 );

add_action( 'wp_body_open', function () {
	if ( ! rip_is_audit_thank_you() ) return;
	echo '<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1686472339304024&amp;ev=Lead&amp;noscript=1" alt=""></noscript>';
} );

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
