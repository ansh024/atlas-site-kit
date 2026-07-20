<?php
/**
 * Custom Post Types for client-replicable City, Industry, Service, and Case
 * Study pages.
 *
 * Each is driven by an ACF field group (see acf-json/) so duplicating a page
 * is just: Add New -> fill in the fields -> Publish. No code required.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'rip_register_post_types' );
function rip_register_post_types() {
	register_post_type( 'rip_city', array(
		'label'         => 'City Pages',
		'labels'        => array(
			'name'          => 'City Pages',
			'singular_name' => 'City Page',
			'add_new_item'  => 'Add New City Page',
			'edit_item'     => 'Edit City Page',
		),
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-location-alt',
		'supports'      => array( 'title' ),
		'rewrite'       => false,
	) );

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

	register_post_type( 'rip_service', array(
		'label'        => 'Service Pages',
		'labels'       => array(
			'name'          => 'Service Pages',
			'singular_name' => 'Service Page',
			'add_new_item'  => 'Add New Service Page',
			'edit_item'     => 'Edit Service Page',
		),
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-search',
		'supports'      => array( 'title' ),
		'rewrite'       => false,
	) );
}

/**
 * Duplicate a City Page and all of its ACF/SEO metadata into a new draft.
 * This is plugin-owned because generic duplicate-page plugins do not
 * consistently support custom post types.
 */
function rip_duplicate_city_post( $source_id ) {
	$source = get_post( $source_id );
	if ( ! $source || $source->post_type !== 'rip_city' ) {
		return new WP_Error( 'rip_invalid_city', 'The source City Page could not be found.' );
	}

	$new_id = wp_insert_post( array(
		'post_type'      => 'rip_city',
		'post_status'    => 'draft',
		'post_title'     => $source->post_title . ' Copy',
		'post_content'   => $source->post_content,
		'post_excerpt'   => $source->post_excerpt,
		'post_author'    => get_current_user_id(),
		'post_parent'    => $source->post_parent,
		'menu_order'     => $source->menu_order,
		'comment_status' => $source->comment_status,
		'ping_status'    => $source->ping_status,
	), true );
	if ( is_wp_error( $new_id ) ) return $new_id;

	$skip_meta = array( '_edit_lock', '_edit_last', '_wp_old_slug' );
	foreach ( get_post_meta( $source_id ) as $key => $values ) {
		if ( in_array( $key, $skip_meta, true ) ) continue;
		foreach ( $values as $value ) {
			add_post_meta( $new_id, $key, maybe_unserialize( $value ) );
		}
	}

	return $new_id;
}

add_filter( 'post_row_actions', 'rip_city_duplicate_row_action', 10, 2 );
function rip_city_duplicate_row_action( $actions, $post ) {
	if ( $post->post_type !== 'rip_city' || ! current_user_can( 'edit_post', $post->ID ) ) return $actions;
	$url = wp_nonce_url(
		admin_url( 'admin.php?action=rip_duplicate_city&post=' . $post->ID ),
		'rip_duplicate_city_' . $post->ID
	);
	$actions['rip_duplicate_city'] = '<a href="' . esc_url( $url ) . '">Duplicate City Page</a>';
	return $actions;
}

add_action( 'admin_action_rip_duplicate_city', 'rip_handle_duplicate_city' );
function rip_handle_duplicate_city() {
	$source_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( ! $source_id || ! current_user_can( 'edit_post', $source_id ) ) {
		wp_die( esc_html__( 'You are not allowed to duplicate this City Page.', 'ranked-international' ) );
	}
	check_admin_referer( 'rip_duplicate_city_' . $source_id );

	$new_id = rip_duplicate_city_post( $source_id );
	if ( is_wp_error( $new_id ) ) {
		wp_die( esc_html( $new_id->get_error_message() ) );
	}
	wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_id ) );
	exit;
}

/**
 * Give City, Industry, and Service posts clean top-level permalinks.
 */
add_filter( 'post_type_link', 'rip_industry_permalink', 10, 2 );
function rip_industry_permalink( $permalink, $post ) {
	if ( in_array( $post->post_type, array( 'rip_city', 'rip_industry', 'rip_service' ), true ) && $post->post_status === 'publish' ) {
		return home_url( '/' . $post->post_name . '/' );
	}
	return $permalink;
}

/**
 * Resolve top-level URLs to City, Industry, or Service posts as a fallback.
 * WordPress first tries to match a Page; only when no Page (or post) exists
 * at that path do we look for one of our published landing posts.
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

	$landing = get_posts( array(
		'post_type'      => array( 'rip_city', 'rip_industry', 'rip_service' ),
		'name'           => $slug,
		'post_status'    => 'publish',
		'posts_per_page' => 1,
	) );
	if ( ! $landing ) return;

	$post_type = $landing[0]->post_type;
	$query->set( 'post_type', $post_type );
	$query->set( $post_type, $slug );
	$query->set( 'name', $slug );
	$query->set( 'pagename', '' );
	$query->is_page     = false;
	$query->is_home     = false;
	$query->is_single   = true;
	$query->is_singular = true;
}

/**
 * Point ACF at the bundled field-group JSON so all reusable page editors
 * appear automatically once ACF is installed.
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
	// Deliberately NOT seeding here: during the activation request ACF has
	// already booted BEFORE this plugin was loaded, so our acf-json field
	// definitions aren't registered yet and update_field() would write the
	// values under wrong meta names. The init-20 hook below seeds on the
	// next request instead, when ACF has loaded our field groups.
}

/**
 * Seed on init priority 20 — after ACF boots (init 5, which loads our
 * acf-json field groups) and after our post types register (init 10). The
 * seeded-option guard inside rip_seed_content() makes this a cheap no-op
 * after the first successful run.
 */
add_action( 'init', 'rip_seed_content', 20 );
add_action( 'init', 'rip_seed_service_content', 21 );
add_action( 'init', 'rip_seed_city_content', 22 );

/**
 * One-time migration from the short-lived Page Template implementation.
 * The strict template check ensures no client-created or unrelated Page can
 * ever be converted. Post meta and publication status are preserved.
 */
add_action( 'init', 'rip_migrate_legacy_city_page', 19 );
function rip_migrate_legacy_city_page() {
	if ( get_option( 'rip_city_cpt_migrated' ) ) return;
	$page = get_page_by_path( 'dallas', OBJECT, 'page' );
	if ( ! $page || get_page_template_slug( $page->ID ) !== 'templates/template-city.php' ) {
		update_option( 'rip_city_cpt_migrated', true );
		return;
	}
	// A separately created City Page wins. Never create duplicate landing
	// records with the same public slug during a recovery or partial rollout.
	if ( get_page_by_path( 'dallas', OBJECT, 'rip_city' ) ) {
		update_option( 'rip_city_cpt_migrated', true );
		return;
	}

	$result = wp_update_post( array( 'ID' => $page->ID, 'post_type' => 'rip_city' ), true );
	if ( is_wp_error( $result ) ) return;
	delete_post_meta( $page->ID, '_wp_page_template' );
	clean_post_cache( $page->ID );
	update_option( 'rip_city_cpt_migrated', true );
}

/**
 * Recovery switch: visiting /wp-admin/?rip_reseed=1 as an administrator
 * deletes the seeded DRAFTS (published or client-renamed posts are left
 * alone) and re-runs the seed. For repairing a site where the seed ran
 * before ACF could resolve our fields. Imported media is reused, not
 * duplicated (see rip_seed_attachment cache).
 */
add_action( 'admin_init', 'rip_maybe_reseed' );
function rip_maybe_reseed() {
	if ( ! isset( $_GET['rip_reseed'] ) || $_GET['rip_reseed'] !== '1' ) return;
	if ( ! current_user_can( 'manage_options' ) ) return;

	$seed_slugs = array(
		'rip_city'      => array( 'dallas' ),
		'rip_case_study' => array( 'alexis-delivery-service', 'bella-med-spa', 'dfw-flower-wall', 'reyes-custom-millwork', 'social-pro-photo-booth', 'turf-and-design' ),
		'rip_industry'   => array( 'construction' ),
		'rip_service'    => array( 'local-seo-services' ),
	);
	foreach ( $seed_slugs as $post_type => $slugs ) {
		$posts = get_posts( array(
			'post_type'      => $post_type,
			'post_name__in'  => $slugs,
			'post_status'    => 'draft', // only drafts — never delete anything the client published
			'posts_per_page' => -1,
			'fields'         => 'ids',
		) );
		foreach ( $posts as $pid ) {
			wp_delete_post( $pid, true );
		}
	}

	delete_option( 'rip_content_seeded' );
	delete_option( 'rip_service_content_seeded' );
	delete_option( 'rip_city_content_seeded' );
	rip_seed_content();
	rip_seed_service_content();
	rip_seed_city_content();

	add_action( 'admin_notices', function () {
		$done = get_option( 'rip_content_seeded' ) ? 'Content re-seeded successfully.' : 'Re-seed could not run — is ACF active?';
		echo '<div class="notice notice-info"><p><strong>Ranked International Pages:</strong> ' . esc_html( $done ) . '</p></div>';
	} );
}

/**
 * Admin notice if ACF isn't installed — the field groups (and therefore the
 * "Add New" screens for these post types) won't show up without it.
 */
add_action( 'admin_notices', 'rip_maybe_notice_acf_missing' );
function rip_maybe_notice_acf_missing() {
	if ( function_exists( 'get_field' ) ) return;
	echo '<div class="notice notice-warning"><p><strong>Ranked International Pages:</strong> this plugin needs Advanced Custom Fields to show the City, Industry, Service, and Case Study field editors. <a href="' . esc_url( admin_url( 'plugin-install.php?s=advanced+custom+fields&tab=search&type=term' ) ) . '">Install it here</a>.</p></div>';
}
