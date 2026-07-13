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
		'rewrite'      => array( 'slug' => '', 'with_front' => false ),
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
 * Admin notice if ACF isn't installed — the field groups (and therefore the
 * "Add New" screens for these post types) won't show up without it.
 */
add_action( 'admin_notices', 'rip_maybe_notice_acf_missing' );
function rip_maybe_notice_acf_missing() {
	if ( function_exists( 'get_field' ) ) return;
	echo '<div class="notice notice-warning"><p><strong>Ranked International Pages:</strong> this plugin needs Advanced Custom Fields (free) to show the Industry Page / Case Study field editors. <a href="' . esc_url( admin_url( 'plugin-install.php?s=advanced+custom+fields&tab=search&type=term' ) ) . '">Install it here</a>.</p></div>';
}
