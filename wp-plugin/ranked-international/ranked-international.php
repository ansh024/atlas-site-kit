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
require_once RIP_DIR . 'includes/editor-guides.php';

/**
 * GHL embed per campaign: form ID, form name and iframe height, exactly as the
 * GHL embed snippet supplies them. Which thank-you page a lead lands on is the
 * form's post-submit redirect, configured inside GHL rather than here — so two
 * campaigns sharing a form ID also share that redirect.
 *
 * To give a campaign its own destination: duplicate the form in GHL, point the
 * copy's redirect at that campaign's thank-you path, then add it below.
 */
function rip_ghl_forms() {
	return array(
		'default'        => array( 'id' => 'NnlAud8uVZoK09OlAAFj', 'name' => 'Meta Form', 'height' => 1000 ),
		'organic'        => array( 'id' => 'f0ApiaQNdHgKKFOqtp8q', 'name' => 'Organic', 'height' => 792 ),
		// Redirect this one to /turf-thank-you/ inside GHL.
		'turf'           => array( 'id' => 'NnlAud8uVZoK09OlAAFj', 'name' => 'Meta Form', 'height' => 1000 ),
		'seo-businesses' => array( 'id' => 'hQTRuheSQNg66eN7NdVt', 'name' => 'Meta Form - General', 'height' => 772 ),
	);
}

function rip_ghl_form( $campaign = 'default' ) {
	$forms = rip_ghl_forms();
	return $forms[ $campaign ] ?? $forms['default'];
}

function rip_ghl_form_id( $campaign = 'default' ) {
	$form = rip_ghl_form( $campaign );
	return $form['id'];
}

/**
 * The audit form as an in-page section, for landing pages that drop the theme
 * footer and so have to carry the form themselves. Every other page scrolls to
 * the footer's form instead (see main.js), so a page must have one audit form
 * or the other — never both, since both answer to the same `#audit` anchor.
 */
function rip_render_inline_ghl_audit( $campaign = 'default', $intro = '', $reassurance = 'No contract. No obligation. Just a clear next step.' ) {
	$form = rip_ghl_form( $campaign );
	$form_id = $form['id'];
	$form_name = $form['name'];
	$form_height = (int) $form['height'];
	?>
	<section class="trade-audit" id="audit" aria-labelledby="auditTitle" style="--rip-audit-h:<?php echo esc_attr( $form_height ); ?>px">
		<div class="trade-audit__inner">
			<div class="trade-audit__value">
				<h2 id="auditTitle">Get a clearer path to more leads.</h2>
				<?php if ( $intro ) : ?><p class="trade-audit__intro"><?php echo esc_html( $intro ); ?></p><?php endif; ?>
				<?php if ( $reassurance ) : ?><p class="trade-audit__reassurance"><?php echo esc_html( $reassurance ); ?></p><?php endif; ?>
			</div>
			<div class="trade-audit__form-panel">
				<iframe class="ghl-audit-form" src="https://api.leadconnectorhq.com/widget/form/<?php echo esc_attr( $form_id ); ?>" id="inline-<?php echo esc_attr( $form_id ); ?>" data-layout="{'id':'INLINE'}" data-trigger-type="alwaysShow" data-activation-type="alwaysActivated" data-deactivation-type="neverDeactivate" data-form-name="<?php echo esc_attr( $form_name ); ?>" data-height="<?php echo esc_attr( $form_height ); ?>" data-layout-iframe-id="inline-<?php echo esc_attr( $form_id ); ?>" data-form-id="<?php echo esc_attr( $form_id ); ?>" title="Request your free SEO audit"></iframe>
			</div>
		</div>
	</section>
	<?php
}

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
		'/seo-audit-thank-you'           => 'audit',
		'/turf-thank-you'                => 'turf',
		'/thank-you-seo-for-businesses-lp' => 'seo-businesses-lp',
		'/thank-you-main-site'             => 'main-site',
	);
}

/** Legal pages are plugin routes so they do not rely on manually-created WP pages. */
function rip_legal_routes() {
	return array(
		'/privacy-policy'   => 'privacy',
		'/terms-of-service' => 'terms',
	);
}

/** The focused homepage clone used by Meta Ads. No database Page is required. */
function rip_is_seo_businesses_landing() {
	if ( is_admin() ) return false;
	$path = untrailingslashit( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
	return $path === '/seo-for-businesses';
}

function rip_legal_route() {
	if ( is_admin() ) return false;
	$path = untrailingslashit( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
	$routes = rip_legal_routes();
	return $routes[ $path ] ?? false;
}

function rip_is_legal_page() {
	return rip_legal_route() !== false;
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

function rip_is_seo_businesses_thank_you() {
	return rip_thank_you_route() === 'seo-businesses-lp';
}

/**
 * The turf campaign's Meta Pixel is managed by hand in WordPress rather than
 * here, so the plugin emits no pixel on this route and leaves whatever is
 * pasted into <head> alone. See the conversion-pixel and head-cleaner hooks.
 */
function rip_is_turf_thank_you() {
	return rip_thank_you_route() === 'turf';
}

add_filter( 'redirect_canonical', function ( $redirect_url ) {
	return ( rip_is_audit_thank_you() || rip_is_legal_page() || rip_is_seo_businesses_landing() ) ? false : $redirect_url;
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
	if ( ! rip_is_audit_thank_you() && ! rip_is_legal_page() && ! rip_is_seo_businesses_landing() ) return;
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
}, 0 );

/**
 * Yoast also filters pre_get_document_title (priority 15), so this has to run
 * after it to win.
 */
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( rip_is_audit_thank_you() ) return 'Thank You | ' . get_bloginfo( 'name' );
	if ( rip_legal_route() === 'privacy' ) return 'Privacy Policy | ' . get_bloginfo( 'name' );
	if ( rip_legal_route() === 'terms' ) return 'Terms of Service | ' . get_bloginfo( 'name' );
	if ( rip_is_seo_businesses_landing() ) return 'SEO for Businesses | ' . get_bloginfo( 'name' );
	return $title;
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
 * Homepage-only ACF values. City Pages deliberately render template-home.php
 * too, so this must be kept separate from rip_home_copy().
 */
function rip_is_home_template_page() {
	return is_page() && get_page_template_slug( get_queried_object_id() ) === 'templates/template-home.php';
}

function rip_home_field( $field, $fallback = '' ) {
	if ( ! rip_is_home_template_page() || ! function_exists( 'get_field' ) ) return $fallback;
	$value = get_field( $field, get_queried_object_id() );
	return $value !== null && $value !== '' ? $value : $fallback;
}

function rip_home_rows( $field, $fallback = array() ) {
	if ( ! rip_is_home_template_page() || ! function_exists( 'get_field' ) ) return $fallback;
	$rows = get_field( $field, get_queried_object_id() );
	return is_array( $rows ) && $rows ? $rows : $fallback;
}

function rip_home_process_icon( $icon ) {
	$icons = array(
		'search' => '<circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path>',
		'calendar' => '<rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"></path>',
		'trend' => '<path d="m3 17 6-6 4 4 8-8"></path><path d="M14 7h7v7"></path>',
		'bars' => '<line x1="18" x2="18" y1="20" y2="10"></line><line x1="12" x2="12" y1="20" y2="4"></line><line x1="6" x2="6" y1="20" y2="14"></line>',
	);
	return $icons[ $icon ] ?? $icons['search'];
}

/**
 * Resolve an optional FAQ repeater without changing existing pages.
 * "default" preserves the supplied built-in rows, "custom" uses only ACF
 * rows (an empty list hides the section), and "hidden" always hides it.
 */
function rip_get_faq_rows( $fallback = array() ) {
	if ( ! function_exists( 'get_field' ) ) return $fallback;

	$mode = get_field( 'faq_mode' ) ?: 'default';
	if ( $mode === 'hidden' ) return array();

	$rows = get_field( 'faqs' );
	if ( is_array( $rows ) && $rows ) return $rows;

	return $mode === 'custom' ? array() : $fallback;
}

function rip_get_field_or( $name, $fallback = '' ) {
	if ( ! function_exists( 'get_field' ) ) return $fallback;
	$value = get_field( $name );
	return $value !== null && $value !== '' ? $value : $fallback;
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
	if ( rip_is_seo_businesses_landing() ) {
		return RIP_DIR . 'templates/template-home.php';
	}
	if ( rip_is_audit_thank_you() ) {
		return RIP_DIR . 'templates/template-audit-thank-you.php';
	}
	if ( rip_is_legal_page() ) {
		return RIP_DIR . 'templates/template-legal.php';
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
	if ( rip_is_audit_thank_you() || rip_is_legal_page() || rip_is_seo_businesses_landing() ) return true;
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
	if ( rip_is_seo_businesses_landing() ) {
		$classes[] = 'rip-seo-businesses-landing';
		return array_unique( $classes );
	}
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
	$is_seo_businesses = rip_is_seo_businesses_landing();
	$is_audit_thank_you = rip_is_audit_thank_you();
	$is_legal_page = rip_is_legal_page();
	if ( $is_case_study ) {
		wp_enqueue_style( 'rip-case-study', RIP_URL . 'assets/css/case-study.css', array( 'rip-styles' ), RIP_VERSION );
	}
	if ( $is_service ) {
		wp_enqueue_style( 'rip-service', RIP_URL . 'assets/css/service.css', array( 'rip-styles' ), RIP_VERSION );
	}
	if ( $is_turf_tree || $is_seo_businesses ) {
		wp_enqueue_style( 'rip-turf-tree', RIP_URL . 'assets/css/trade-landing.css', array( 'rip-styles', 'rip-page-fixes' ), RIP_VERSION );
	}
	if ( $is_audit_thank_you ) {
		wp_enqueue_style( 'rip-audit-thank-you', RIP_URL . 'assets/css/thank-you.css', array( 'rip-styles', 'rip-page-fixes' ), RIP_VERSION );
	}
	if ( $is_legal_page ) {
		wp_enqueue_style( 'rip-legal', RIP_URL . 'assets/css/legal.css', array( 'rip-styles', 'rip-page-fixes' ), RIP_VERSION );
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
	if ( $is_turf_tree || $is_seo_businesses ) {
		wp_enqueue_script( 'rip-turf-tree', RIP_URL . 'assets/js/trade-landing.js', array( 'gsap', 'gsap-scrolltrigger', 'rip-main' ), RIP_VERSION, true );
	}
	// The landers embed the GHL form in the page, so they need the resize script.
	// Everywhere else the form lives in the theme footer, which ships its own copy.
	if ( $is_turf_tree || $is_seo_businesses ) {
		wp_enqueue_script( 'rip-ghl-form-embed', 'https://link.msgsndr.com/js/form_embed.js', array(), null, true );
	}
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
	if ( rip_is_turf_tree_landing() || rip_is_seo_businesses_landing() ) ob_start();
} );

add_action( 'wp_footer', function () {
	if ( ( ! rip_is_turf_tree_landing() && ! rip_is_seo_businesses_landing() ) || ! ob_get_level() ) return;
	$footer = ob_get_clean();

	$cleaned = preg_replace( '#<footer\b[^>]*id=["\']uicore-tb-footer["\'][^>]*>.*?</footer>#is', '', $footer );

	echo $cleaned === null ? $footer : $cleaned; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}, 0 );

/** Meta PageView pixel for the SEO for Businesses paid-traffic campaign. */
add_action( 'wp_head', function () {
	if ( ! rip_is_seo_businesses_landing() ) return;
	?>
	<!-- Meta Pixel Code -->
	<script id="rip-meta-seo-businesses">
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init','1975861066398590');
	fbq('track','PageView');
	</script>
	<!-- End Meta Pixel Code -->
	<?php
}, 20 );

/**
 * Meta conversion events are intentionally limited to post-submission routes.
 * The turf thank-you is excluded: that campaign's pixel is pasted into
 * WordPress by hand, so emitting one here would double-count every Lead.
 */
add_action( 'wp_head', function () {
	if ( ! rip_is_audit_thank_you() || rip_is_turf_thank_you() ) return;
	$seo_businesses = rip_is_seo_businesses_thank_you();
	?>
	<!-- Meta Pixel Code -->
	<script id="<?php echo $seo_businesses ? 'rip-meta-seo-businesses-conversion' : 'rip-meta-lead'; ?>">
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
	fbq('init','<?php echo $seo_businesses ? '1975861066398590' : '1686472339304024'; ?>');
	<?php if ( $seo_businesses ) : ?>fbq('track','PageView');
	<?php endif; ?>
	fbq('track','Lead');
	</script>
	<?php if ( $seo_businesses ) : ?>
	<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1975861066398590&amp;ev=PageView&amp;noscript=1" alt=""></noscript>
	<?php endif; ?>
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
 *
 * The SEO-businesses thank-you is the one exception: it must carry pixel
 * 1975861066398590 and nothing else. `fbq('track', …)` broadcasts to every
 * initialised pixel, so leaving the site-wide 1686472339304024 block in that
 * page's <head> would silently bill it a second PageView plus the campaign's
 * Lead. On that route every foreign Meta Pixel block is dropped.
 *
 * The turf thank-you is skipped entirely. Its pixel is hand-managed in
 * WordPress, and that pasted block is precisely the Lead-firing kind this
 * filter deletes — running here would strip the only pixel the page has.
 */
add_action( 'wp_head', function () {
	if ( rip_is_audit_thank_you() && ! rip_is_turf_thank_you() ) ob_start();
}, 0 );

add_action( 'wp_head', function () {
	if ( ! rip_is_audit_thank_you() || rip_is_turf_thank_you() || ! ob_get_level() ) return;
	$head          = ob_get_clean();
	$seo_exclusive = rip_is_seo_businesses_thank_you();

	$cleaned = preg_replace_callback(
		'#<!--\s*Meta Pixel Code\s*-->.*?<!--\s*End Meta Pixel Code\s*-->#is',
		function ( $match ) use ( $seo_exclusive ) {
			$block = $match[0];
			// Keep our own block, always.
			if ( strpos( $block, 'rip-meta-lead' ) !== false || strpos( $block, 'rip-meta-seo-businesses-conversion' ) !== false ) return $block;
			// On the SEO-businesses thank-you, ours is the only pixel allowed.
			if ( $seo_exclusive ) return '';
			if ( ! preg_match( '#fbq\(\s*[\'"]track[\'"]\s*,\s*[\'"]Lead[\'"]#i', $block ) ) return $block;
			return '';
		},
		$head
	);

	echo $cleaned === null ? $head : $cleaned; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}, 999 );

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
