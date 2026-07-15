<?php
/**
 * Custom Post Types for the two client-replicable page types:
 * Industry Pages (e.g. /construction/, /roofing/) and Case Studies
 * (e.g. /case-studies/alexis-delivery-service/).
 *
 * Each is driven by an ACF field group (see acf-json/) so duplicating a page
 * is just: Add New -> fill in the fields -> Publish. No code required.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'rip_register_post_types' );
function rip_register_post_types() {
	// Industry Pages live at top-level URLs (/construction/, /hvac/, ...) but
	// deliberately do NOT register a rewrite rule: a top-level CPT rule would
	// intercept EVERY top-level URL and 404 the site's existing Pages
	// (/about-us/, /contact/, the old service pages, etc). Instead we resolve
	// them as a fallback in rip_industry_url_fallback() below — an existing
	// WordPress Page with the same slug always wins.
	register_post_type( 'rip_industry', array(
		'label'        => 'Industry Pages',
		'labels'       => array(
			'name'          => 'Industry Pages',
			'singular_name' => 'Industry Page',
			'add_new_item'  => 'Add New Industry Page',
			'edit_item'     => 'Edit Industry Page',
		),
		'public'       => true,
		'show_in_rest' => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-hammer',
		'supports'     => array( 'title' ),
		'rewrite'      => false,
	) );

	register_post_type( 'rip_case_study', array(
		'label'        => 'Case Studies',
		'labels'       => array(
			'name'          => 'Case Studies',
			'singular_name' => 'Case Study',
			'add_new_item'  => 'Add New Case Study',
			'edit_item'     => 'Edit Case Study',
		),
		'public'       => true,
		'show_in_rest' => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-chart-line',
		'supports'     => array( 'title' ),
		'rewrite'      => array( 'slug' => 'case-studies', 'with_front' => false ),
	) );
}

/**
 * Give Industry Page posts clean top-level permalinks (/construction/).
 */
add_filter( 'post_type_link', 'rip_industry_permalink', 10, 2 );
function rip_industry_permalink( $permalink, $post ) {
	if ( $post->post_type === 'rip_industry' && $post->post_status === 'publish' ) {
		return home_url( '/' . $post->post_name . '/' );
	}
	return $permalink;
}

/**
 * Resolve top-level URLs to Industry Page posts — but only as a FALLBACK.
 * WordPress first tries to match a Page; only when no Page (or post) exists
 * at that path do we look for a published rip_industry post with that slug.
 * This guarantees activating the plugin can never hijack or 404 any URL the
 * site already serves.
 */
add_action( 'pre_get_posts', 'rip_industry_url_fallback' );
function rip_industry_url_fallback( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) return;

	// Depending on the permalink structure, an unmatched top-level URL
	// arrives as either 'pagename' (e.g. /%year%/%postname%/ structures) or
	// 'name' (/%postname%/ structure, which rankedinternational.com uses).
	$slug = $query->get( 'pagename' );
	if ( ! $slug && ! $query->get( 'post_type' ) ) {
		$slug = $query->get( 'name' );
	}
	if ( ! $slug || strpos( $slug, '/' ) !== false ) return; // top-level only

	// Anything the site already serves at this slug wins — never hijack.
	if ( get_page_by_path( $slug ) ) return;                  // existing Page
	if ( get_page_by_path( $slug, OBJECT, 'post' ) ) return;  // existing blog post

	$industry = get_posts( array(
		'post_type'      => 'rip_industry',
		'name'           => $slug,
		'post_status'    => 'publish',
		'posts_per_page' => 1,
	) );
	if ( ! $industry ) return;

	$query->set( 'post_type', 'rip_industry' );
	$query->set( 'rip_industry', $slug );
	$query->set( 'name', $slug );
	$query->set( 'pagename', '' );
	$query->is_page     = false;
	$query->is_home     = false;
	$query->is_single   = true;
	$query->is_singular = true;
}

/**
 * Point ACF at this plugin's bundled field group JSON so the "Industry Page
 * Details" / "Case Study Details" field groups appear automatically once ACF
 * is installed — no manual field setup needed.
 */
add_filter( 'acf/settings/load_json', 'rip_acf_json_load_point' );
function rip_acf_json_load_point( $paths ) {
	$paths[] = RIP_DIR . 'acf-json';
	return $paths;
}

/**
 * Flush rewrite rules once so the new /industry-slug/ and /case-studies/slug/
 * permalinks work immediately after activation, without a manual
 * Settings -> Permalinks -> Save trip.
 */
register_activation_hook( RIP_DIR . 'ranked-international.php', 'rip_on_activate' );
function rip_on_activate() {
	rip_register_post_types();
	flush_rewrite_rules();
	rip_seed_content();
}

/**
 * If ACF wasn't active yet when this plugin was activated, the seed is
 * skipped (it needs update_field()). Retry on init — priority 20 so it runs
 * AFTER both ACF boots (init 5) and our post types register (init 10). The
 * seeded-option guard inside rip_seed_content() makes this a cheap no-op
 * after the first successful run.
 */
add_action( 'init', 'rip_seed_content', 20 );

/**
 * Admin notice if ACF isn't installed — the field groups (and therefore the
 * "Add New" screens for these post types) won't show up without it.
 */
add_action( 'admin_notices', 'rip_maybe_notice_acf_missing' );
function rip_maybe_notice_acf_missing() {
	if ( function_exists( 'get_field' ) ) return;
	echo '<div class="notice notice-warning"><p><strong>Ranked International Pages:</strong> this plugin needs Advanced Custom Fields (free) to show the Industry Page / Case Study field editors. <a href="' . esc_url( admin_url( 'plugin-install.php?s=advanced+custom+fields&tab=search&type=term' ) ) . '">Install it here</a>.</p></div>';
}
