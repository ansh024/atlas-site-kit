<?php
/**
 * One-time seed: migrates the original static construction page and all 6
 * case studies into the new rip_industry / rip_case_study post types, so
 * nothing is lost when switching from one-off PHP templates to client-
 * editable ACF fields. Runs once on plugin activation (see rip_on_activate()
 * in includes/cpt.php) and is idempotent — safe if activation runs twice.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function rip_seed_content() {
	if ( get_option( 'rip_content_seeded' ) ) return;
	if ( ! function_exists( 'update_field' ) ) return; // ACF not active yet — nothing to populate safely

	// ACF must be able to resolve our field definitions (loaded from
	// acf-json at ACF boot), or update_field() would silently write values
	// under wrong meta names. If it can't, bail WITHOUT setting the seeded
	// flag — the init hook retries on the next request.
	if ( ! function_exists( 'acf_get_field' ) || ! acf_get_field( 'field_rip_cs_client_name' ) ) return;

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$cs_ids = rip_seed_case_studies();
	rip_seed_construction();
	rip_link_related_case_studies( $cs_ids );

	update_option( 'rip_content_seeded', true );
}

/**
 * ACF's update_field() only works with field NAMES on posts that were
 * previously saved through the ACF UI (the field-key reference must already
 * exist in postmeta). For brand-new seeded posts we must write by field KEY,
 * or get_field()/have_rows() will silently return nothing on the front end.
 * Build the name=>key map from the same acf-json files ACF itself loads.
 */
function rip_field_key_map( $group_file ) {
	static $maps = array();
	if ( ! isset( $maps[ $group_file ] ) ) {
		$json = json_decode( file_get_contents( RIP_DIR . 'acf-json/' . $group_file ), true );
		$map  = array();
		foreach ( ( $json['fields'] ?? array() ) as $f ) {
			$map[ $f['name'] ] = $f['key'];
		}
		$maps[ $group_file ] = $map;
	}
	return $maps[ $group_file ];
}

function rip_update_fields( $post_id, $fields, $group_file ) {
	$keys = rip_field_key_map( $group_file );
	foreach ( $fields as $name => $value ) {
		update_field( $keys[ $name ] ?? $name, $value, $post_id );
	}
}

/**
 * PHP 7.2-safe replacement for the arrow-function one-liners.
 */
function rip_chart_points( $values ) {
	return array_map( function ( $v ) { return array( 'value' => $v ); }, $values );
}

/**
 * Imports one of this plugin's bundled images into the Media Library so it
 * can be used as an ACF image field value (which stores an attachment ID).
 * Cached in an option so repeat seeding doesn't duplicate media items.
 */
function rip_seed_attachment( $relative_path ) {
	static $cache = null;
	if ( $cache === null ) $cache = get_option( 'rip_seed_attachment_cache', array() );
	if ( isset( $cache[ $relative_path ] ) ) return $cache[ $relative_path ];

	$file_path = RIP_DIR . 'assets/images/' . $relative_path;
	if ( ! file_exists( $file_path ) ) return 0;

	$filetype = wp_check_filetype( basename( $file_path ) );
	$upload   = wp_upload_bits( basename( $file_path ), null, file_get_contents( $file_path ) );
	if ( ! empty( $upload['error'] ) ) return 0;

	$attachment_id = wp_insert_attachment( array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( basename( $file_path ) ),
		'post_status'    => 'inherit',
	), $upload['file'] );

	if ( $attachment_id ) {
		$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}

	$cache[ $relative_path ] = $attachment_id;
	update_option( 'rip_seed_attachment_cache', $cache );
	return $attachment_id;
}

function rip_seed_case_studies() {
	$logos = array(
		'alexis-delivery-service' => 'brand-logos/11.png',
		'bella-med-spa'           => 'brand-logos/8.png',
		'dfw-flower-wall'         => 'brand-logos/6.png',
		'reyes-custom-millwork'   => 'brand-logos/7.png',
		'social-pro-photo-booth'  => 'brand-logos/10.png',
		'turf-and-design'         => 'brand-logos/9.png',
	);

	$data = array(
		'alexis-delivery-service' => array(
			'title' => 'Alexis Delivery Service',
			'fields' => array(
				'client_name' => 'Alexis Delivery Service',
				'industry_tag' => 'Dallas-Fort Worth · Moving & Delivery',
				'live_url' => 'https://www.alexisdeliveryservice.com/',
				'hero_title' => 'Building a <em>25-city</em> moving empire, one page at a time.',
				'hero_sub' => "Alexis Delivery Service moves families across the DFW metro. Twelve months in, we're mid-buildout — with dedicated pages already ranking in over a dozen cities.",
				'hero_stats' => array(
					array( 'label' => 'City landing pages already live', 'value' => '25<span>+</span>' ),
					array( 'label' => 'Best current city ranking', 'value' => '#3' ),
					array( 'label' => 'Keywords already inside the top 20', 'value' => '8' ),
				),
				'chart_current_value' => '19', 'chart_current_unit' => 'visitors / month',
				'chart_badge' => 'Campaign live 12 months — still climbing',
				'chart_start_label' => 'May 2025', 'chart_end_label' => 'May 2026',
				'chart_points' => rip_chart_points( array( 6,7,5,8,10,11,17,19,19,29,13,15,15 ) ),
				'context_pills' => array(
					array( 'icon' => 'tag', 'text' => 'Moving & delivery services' ),
					array( 'icon' => 'map-pin', 'text' => 'Based in DFW · 25+ city pages' ),
					array( 'icon' => 'calendar', 'text' => '12-month engagement, in progress' ),
					array( 'icon' => 'search', 'text' => 'Local SEO + city landing pages' ),
				),
				'strategy_title' => 'The buildout comes<br>before the traffic.',
				'strategy_lede' => "Alexis Delivery Service is proof the buildout comes before the traffic. <em>Twenty-five city pages don't rank overnight — they rank one at a time.</em>",
				'strategy_cards' => array(
					array( 'title' => 'One page, one city, at a time', 'text' => 'Watauga, Weatherford, Burleson, Rockwall, Haslet, and 20 more — each with its own optimized moving-company page.' ),
					array( 'title' => 'Chasing the movers', 'text' => 'Burleson jumped 84 spots, Southlake climbed 66, Saginaw climbed 61 — early proof the pages are gaining ground fast.' ),
					array( 'title' => 'Building toward the big keywords', 'text' => 'Dallas, Fort Worth, and Plano moving searches — 18,000+ searches a month — are next once the smaller cities are locked down.' ),
				),
				'results_title' => 'Early, honest, <em>and climbing</em>.',
				'results_metrics' => array(
					array( 'label' => 'Monthly organic traffic', 'value' => '19', 'sub' => 'up from a standing start, 12 months in' ),
					array( 'label' => 'City pages live', 'value' => '25<span>+</span>', 'sub' => 'across the DFW metro' ),
					array( 'label' => 'Best ranking', 'value' => '#3', 'sub' => '"moving company watauga"' ),
					array( 'label' => 'Keywords climbing', 'value' => '8', 'sub' => 'already inside the top 20' ),
				),
				'table_source' => 'Source: live rank tracking, DFW',
				'keyword_rows' => array(
					array( 'keyword' => 'moving company watauga', 'position' => '#3', 'volume' => '—' ),
					array( 'keyword' => 'weatherford moving company', 'position' => '#10', 'volume' => '480/mo' ),
					array( 'keyword' => 'moving company weatherford', 'position' => '#14', 'volume' => '390/mo' ),
					array( 'keyword' => 'moving company burleson', 'position' => '#16', 'volume' => '170/mo' ),
					array( 'keyword' => 'moving company haslet', 'position' => '#17', 'volume' => '10/mo' ),
					array( 'keyword' => 'moving company rockwall', 'position' => '#17', 'volume' => '320/mo' ),
				),
			),
		),
		'bella-med-spa' => array(
			'title' => 'Bella MedSpa',
			'fields' => array(
				'client_name' => 'Bella MedSpa & Aesthetics',
				'industry_tag' => 'Dallas · Medical Spa & Aesthetics',
				'hero_title' => '7.5&times; the organic traffic for a <em>Dallas med spa</em>.',
				'hero_sub' => 'Bella MedSpa & Aesthetics wanted more of the right patients finding them on Google — not just more ad spend. Organic search now carries the load.',
				'hero_stats' => array(
					array( 'label' => 'Monthly organic visitors, up from 200', 'value' => '1,500' ),
					array( 'label' => 'Traffic growth multiple', 'value' => '7.5<span>&times;</span>' ),
				),
				'chart_current_value' => '1,500', 'chart_current_unit' => 'visitors / month',
				'chart_badge' => '7.5&times; growth',
				'chart_start_label' => 'Campaign start', 'chart_end_label' => 'Today',
				'chart_points' => rip_chart_points( array( 200,220,250,290,340,400,470,550,650,780,950,1150,1500 ) ),
				'context_pills' => array(
					array( 'icon' => 'tag', 'text' => 'Medical spa & aesthetics' ),
					array( 'icon' => 'map-pin', 'text' => 'Based in Dallas, TX' ),
					array( 'icon' => 'calendar', 'text' => 'Ongoing engagement' ),
					array( 'icon' => 'search', 'text' => 'Local SEO' ),
				),
				'strategy_title' => 'Made organic the<br>primary channel.',
				'strategy_lede' => 'Med spas live or die on trust signals and local search — not ad spend alone. <em>Bella needed Google to become the primary channel, not paid ads.</em>',
				'strategy_cards' => array(
					array( 'title' => 'Rebuilt for local intent', 'text' => 'Optimized service pages and Google Business Profile so "med spa near me" and treatment-specific searches lead straight to Bella.' ),
					array( 'title' => 'Built the trust signals Google rewards', 'text' => 'Reviews, before/after content, and provider credentials — the E-E-A-T signals medical and aesthetic searches are held to.' ),
					array( 'title' => 'Let organic replace paid', 'text' => "As rankings climbed, organic search went from an afterthought to Bella's primary new-patient channel." ),
				),
				'results_title' => 'Growth Bella can <em>feel</em>.',
				'results_metrics' => array(
					array( 'label' => 'Monthly organic traffic', 'value' => '1,500', 'sub' => 'up from 200 visitors/month' ),
					array( 'label' => 'Growth multiple', 'value' => '7.5<span>&times;</span>', 'sub' => 'and still compounding' ),
				),
			),
		),
		'dfw-flower-wall' => array(
			'title' => 'DFW Flower Wall',
			'fields' => array(
				'client_name' => 'DFW Flower Wall',
				'industry_tag' => 'Dallas · Event & Party Rentals',
				'live_url' => 'https://www.dfwflowerwall.com/',
				'hero_title' => "From zero to <em>DFW's #1 party rental</em> brand.",
				'hero_sub' => 'DFW Flower Wall rents flower walls, neon signs, and event décor across North Texas. We gave every rental category its own page — then took over the category.',
				'hero_stats' => array(
					array( 'label' => 'Monthly organic visitors, up from 0', 'value' => '1,297' ),
					array( 'label' => 'Keywords ranking <strong>#1</strong> on Google', 'value' => '20' ),
					array( 'label' => 'Keywords ranking in the <strong>top 3</strong>', 'value' => '29' ),
				),
				'chart_current_value' => '1,297', 'chart_current_unit' => 'visitors / month',
				'chart_badge' => 'Built from zero in 24 months',
				'chart_start_label' => 'Jun 2024', 'chart_end_label' => 'Apr 2026',
				'chart_points' => rip_chart_points( array( 130,128,125,110,102,58,32,138,90,150,145,110,60,68,50,55,55,32,15,63,68,88,88,55 ) ),
				'context_pills' => array(
					array( 'icon' => 'tag', 'text' => 'Event & party rentals' ),
					array( 'icon' => 'map-pin', 'text' => 'Based in Dallas, TX · serves DFW' ),
					array( 'icon' => 'calendar', 'text' => '24-month engagement' ),
					array( 'icon' => 'search', 'text' => 'Local SEO + category pages' ),
				),
				'strategy_title' => 'Every rental type<br>became its own front door.',
				'strategy_lede' => "DFW Flower Wall had the inventory — flower walls, shimmer walls, neon signs, balloon installs — but zero organic footprint. Google didn't know they existed. <em>Every rental type needed to become its own front door.</em>",
				'strategy_cards' => array(
					array( 'title' => 'Built a page for every rental category', 'text' => 'Shimmer walls, neon signs, uplighting, candy carts, claw machines, bar rentals — each got a dedicated, optimized page instead of one crowded "party rentals" listing.' ),
					array( 'title' => 'Owned the core "flower wall" terms first', 'text' => 'Flower wall rental dallas, flower wall for rent, rent flower wall — the highest-intent searches in their category, locked down at #1 before expanding outward.' ),
					array( 'title' => 'Expanded into out-of-market cities', 'text' => 'Added landing pages for Houston, Austin, San Antonio, and beyond to catch couples and planners searching outside DFW for the same rentals.' ),
				),
				'results_title' => 'Zero to <em>category leader</em>.',
				'results_metrics' => array(
					array( 'label' => 'Monthly organic traffic', 'value' => '1,297', 'sub' => 'up from 0 · 24 months' ),
					array( 'label' => '#1 rankings', 'value' => '20', 'sub' => 'across flower walls, neon, balloons, rentals' ),
					array( 'label' => 'Top-3 rankings', 'value' => '29', 'sub' => 'out of 115 tracked keywords' ),
					array( 'label' => 'Rental categories ranking', 'value' => '15<span>+</span>', 'sub' => 'from flower walls to claw machines' ),
				),
				'table_source' => 'Source: live rank tracking, Dallas/TX',
				'keyword_rows' => array(
					array( 'keyword' => 'flower wall rental dallas', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'flower wall for rent', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'shimmer wall rental dallas', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'neon sign rental dallas', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'dallas balloon company', 'position' => '#1', 'volume' => '20/mo' ),
					array( 'keyword' => 'furniture rental dallas', 'position' => '#37', 'volume' => '210/mo' ),
				),
			),
		),
		'reyes-custom-millwork' => array(
			'title' => 'Reyes Custom Millwork',
			'fields' => array(
				'client_name' => 'Reyes Custom Millwork',
				'industry_tag' => 'Dallas · Custom Cabinetry & Millwork',
				'live_url' => 'https://reyescustommillwork.com/',
				'hero_title' => '#1 for <em>Dallas custom cabinets</em>, 6.7&times; the traffic.',
				'hero_sub' => 'Reyes Custom Millwork builds custom cabinetry for Dallas homes. We took a near-invisible site and made it the first name Google gives homeowners.',
				'hero_stats' => array(
					array( 'label' => 'Monthly organic visitors, up from 35', 'value' => '234' ),
					array( 'label' => 'Position for "dallas custom cabinets"', 'value' => '#1' ),
					array( 'label' => 'Traffic growth multiple', 'value' => '6.7<span>&times;</span>' ),
				),
				'chart_current_value' => '234', 'chart_current_unit' => 'visitors / month',
				'chart_badge' => '6.7&times; growth in 24 months',
				'chart_start_label' => 'Jun 2024', 'chart_end_label' => 'Apr 2026',
				'chart_points' => rip_chart_points( array( 35,42,50,68,105,75,80,32,36,42,32,36,45,37,45,44,80,84,42,42,45,45,90,120 ) ),
				'context_pills' => array(
					array( 'icon' => 'tag', 'text' => 'Custom cabinetry & millwork' ),
					array( 'icon' => 'map-pin', 'text' => 'Based in Dallas, TX' ),
					array( 'icon' => 'calendar', 'text' => '24-month engagement' ),
					array( 'icon' => 'search', 'text' => 'Local SEO' ),
				),
				'strategy_title' => 'One keyword, locked down first.',
				'strategy_lede' => "Reyes had the craftsmanship. What they didn't have was a single page ranking for the searches homeowners actually type. <em>We fixed the second part.</em>",
				'strategy_cards' => array(
					array( 'title' => 'Locked down the money keyword', 'text' => '"Dallas custom cabinets" and "custom cabinets dallas" — the exact phrases homeowners search — now sit at #1 and #3.' ),
					array( 'title' => 'Built topical depth around millwork', 'text' => 'Supporting pages and on-site content around custom millwork, not just cabinets, to widen the net without diluting the core keyword.' ),
					array( 'title' => 'Kept it local, not generic', 'text' => 'Every page speaks Dallas first — no chasing the unwinnable national "custom cabinets" keyword while ignoring the local searches that actually convert.' ),
				),
				'results_title' => 'The <em>#1</em> spot in Dallas cabinets.',
				'results_metrics' => array(
					array( 'label' => 'Monthly organic traffic', 'value' => '234', 'sub' => 'up from 35 · 6.7&times; growth' ),
					array( 'label' => '#1 rankings', 'value' => '2', 'sub' => '"dallas custom cabinets" + brand name' ),
					array( 'label' => 'Top-3 rankings', 'value' => '4', 'sub' => 'of 5 core keywords tracked' ),
				),
				'table_source' => 'Source: live rank tracking, Dallas/TX',
				'keyword_rows' => array(
					array( 'keyword' => 'dallas custom cabinets', 'position' => '#1', 'volume' => '70/mo' ),
					array( 'keyword' => 'reyes custom millwork', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'custom millwork dallas', 'position' => '#2', 'volume' => '10/mo' ),
					array( 'keyword' => 'custom cabinets dallas', 'position' => '#3', 'volume' => '70/mo' ),
					array( 'keyword' => 'custom cabinets', 'position' => '#11', 'volume' => '210/mo' ),
				),
			),
		),
		'social-pro-photo-booth' => array(
			'title' => 'Social Pro Photo Booth',
			'fields' => array(
				'client_name' => 'Social Pro Photo Booth',
				'industry_tag' => 'Dallas · Experiential & Brand Activation',
				'live_url' => 'https://socialprophotobooth.com/',
				'hero_title' => 'From one Dallas photo booth to <em>#1 in 24 cities</em> nationwide.',
				'hero_sub' => 'Social Pro Photo Booth builds photo booths, video walls, and brand activations for events across the country. We built out a page for every city and every product they rent — then ranked them.',
				'hero_stats' => array(
					array( 'label' => 'Monthly organic visitors, up from 680', 'value' => '1,632' ),
					array( 'label' => 'Keywords ranking <strong>#1</strong> on Google', 'value' => '24' ),
					array( 'label' => 'Keywords ranking in the <strong>top 3</strong>', 'value' => '70<span>+</span>' ),
				),
				'chart_current_value' => '1,632', 'chart_current_unit' => 'visitors / month',
				'chart_badge' => '2.4&times; growth in 24 months',
				'chart_start_label' => 'Jun 2024', 'chart_end_label' => 'Apr 2026',
				'chart_points' => rip_chart_points( array( 680,700,780,800,850,830,800,970,955,1010,985,995,1080,1105,1160,1180,1250,1140,1300,1150,1230,750,760,1632 ) ),
				'context_pills' => array(
					array( 'icon' => 'tag', 'text' => 'Experiential marketing & event rentals' ),
					array( 'icon' => 'map-pin', 'text' => 'Based in Dallas, TX · ranking nationwide' ),
					array( 'icon' => 'calendar', 'text' => '24-month engagement' ),
					array( 'icon' => 'search', 'text' => 'Local SEO + national landing pages' ),
				),
				'strategy_title' => 'One page per city.<br>One page per product.',
				'strategy_lede' => "Social Pro doesn't just serve Dallas — they ship photo booths, LED walls, and activations to events across the country. <em>The gap wasn't demand. It was that Google had no idea they served 40+ other cities.</em>",
				'strategy_cards' => array(
					array( 'title' => 'Built a page for every market', 'text' => 'Dedicated, optimized pages for every city they travel to — Austin, Charlotte, Memphis, New York, even Cabo San Lucas — instead of one generic "photo booth rental" page trying to rank everywhere.' ),
					array( 'title' => 'Ranked every product, not just the brand', 'text' => 'Mirror booths, 360 booths, testimonial video booths, message booths, glambots — each rental type got its own page so buyers searching for a specific product found Social Pro first.' ),
					array( 'title' => 'Moved up-market into brand activations', 'text' => 'Layered in higher-value B2B terms — "brand activation agency," "corporate event activations," "AI photo booth" — positioning Social Pro alongside experiential marketing agencies, not just party rental sites.' ),
				),
				'results_title' => 'The numbers Google <em>agrees</em> with.',
				'results_metrics' => array(
					array( 'label' => 'Monthly organic traffic', 'value' => '1,632', 'sub' => 'up from 680 · 2.4&times; in 24 months' ),
					array( 'label' => '#1 rankings', 'value' => '24', 'sub' => 'spanning Dallas and 15+ other U.S. cities' ),
					array( 'label' => 'Top-3 rankings', 'value' => '70<span>+</span>', 'sub' => 'out of 226 tracked keywords' ),
					array( 'label' => 'City pages ranking', 'value' => '40<span>+</span>', 'sub' => 'from Austin to Cabo San Lucas' ),
				),
				'table_source' => 'Source: live rank tracking, Dallas/TX & national',
				'keyword_rows' => array(
					array( 'keyword' => 'photo booth rental dallas', 'position' => '#1', 'volume' => '390/mo' ),
					array( 'keyword' => 'rent photo booth dallas', 'position' => '#1', 'volume' => '390/mo' ),
					array( 'keyword' => 'testimonial video booth', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'photo booth rental austin', 'position' => '#1', 'volume' => '40/mo' ),
					array( 'keyword' => 'brand activation agency', 'position' => '#8', 'volume' => '10/mo' ),
					array( 'keyword' => 'photo booth', 'position' => '#23', 'volume' => '720/mo' ),
				),
			),
		),
		'turf-and-design' => array(
			'title' => 'TX Artificial Turf & Design',
			'fields' => array(
				'client_name' => 'TX Artificial Turf & Design',
				'industry_tag' => 'Dallas-Fort Worth · Artificial Turf',
				'live_url' => 'https://www.turfanddesign.com/',
				'hero_title' => 'Zero to <em>Domain Authority 55</em> and #1 in Dallas turf.',
				'hero_sub' => 'TX Artificial Turf & Design installs synthetic turf across DFW. We built city-by-city pages and backed them with real authority — now they own the category.',
				'hero_stats' => array(
					array( 'label' => 'Monthly organic visitors, up from 0', 'value' => '221' ),
					array( 'label' => 'Keywords ranking <strong>#1</strong> on Google', 'value' => '5' ),
					array( 'label' => 'Domain Authority', 'value' => '55' ),
				),
				'chart_current_value' => '221', 'chart_current_unit' => 'visitors / month',
				'chart_badge' => 'Broke out after 18 months of building authority',
				'chart_start_label' => 'Jun 2024', 'chart_end_label' => 'Apr 2026',
				'chart_points' => rip_chart_points( array( 45,50,25,25,35,15,5,15,26,50,50,32,45,30,20,15,10,15,90,140,135,150,135,120 ) ),
				'context_pills' => array(
					array( 'icon' => 'tag', 'text' => 'Artificial turf installation' ),
					array( 'icon' => 'map-pin', 'text' => 'Richardson, Plano, Fort Worth, Frisco' ),
					array( 'icon' => 'calendar', 'text' => '24-month engagement' ),
					array( 'icon' => 'search', 'text' => 'Local SEO + link building' ),
				),
				'strategy_title' => 'It took 18 months.<br>Then it compounded.',
				'strategy_lede' => "Turf and Design's growth wasn't a straight line — it took 18 months of city pages and backlinks before Google agreed. <em>Then the compounding kicked in.</em>",
				'strategy_cards' => array(
					array( 'title' => 'City-by-city landing pages', 'text' => 'Dedicated pages for Richardson, Plano, Frisco, and Fort Worth so each market got its own #1-ranking front door, not one generic "Dallas turf" page.' ),
					array( 'title' => 'Built real domain authority', 'text' => '4,400+ backlinks and 275 ranking keywords later, the site now carries enough authority to compete with national turf franchises for the biggest local terms.' ),
					array( 'title' => 'Let the late breakout compound', 'text' => 'Traffic sat flat for over a year before a step-change in late 2025 — proof that local SEO authority pays off in a jump, not a slope.' ),
				),
				'results_title' => 'Authority Google <em>trusts</em>.',
				'results_metrics' => array(
					array( 'label' => 'Monthly organic traffic', 'value' => '221', 'sub' => 'up from 0 · breakout after 18 months' ),
					array( 'label' => '#1 rankings', 'value' => '5', 'sub' => 'turf company dallas & richardson terms' ),
					array( 'label' => 'Domain Authority', 'value' => '55', 'sub' => '"Great" tier for a local install business' ),
					array( 'label' => 'Backlinks', 'value' => '4,400<span>+</span>', 'sub' => 'across 275 ranking keywords' ),
				),
				'table_source' => 'Source: live rank tracking, DFW',
				'keyword_rows' => array(
					array( 'keyword' => 'turf company dallas', 'position' => '#1', 'volume' => '—' ),
					array( 'keyword' => 'dallas turf company', 'position' => '#1', 'volume' => '10/mo' ),
					array( 'keyword' => 'richardson turf company', 'position' => '#1', 'volume' => '—' ),
					array( 'keyword' => 'turf company plano', 'position' => '#2', 'volume' => '—' ),
					array( 'keyword' => 'artificial turf dallas', 'position' => '#4', 'volume' => '480/mo' ),
					array( 'keyword' => 'dallas turf installation', 'position' => '#8', 'volume' => '140/mo' ),
				),
			),
		),
	);

	$ids = array();
	foreach ( $data as $slug => $cs ) {
		$post_id = wp_insert_post( array(
			'post_type'   => 'rip_case_study',
			'post_title'  => $cs['title'],
			'post_name'   => $slug,
			'post_status' => 'draft', // nothing goes live on activation — publish each one deliberately from wp-admin
		) );
		if ( is_wp_error( $post_id ) || ! $post_id ) continue;

		$logo_id = rip_seed_attachment( $logos[ $slug ] );
		if ( $logo_id ) $cs['fields']['client_logo'] = $logo_id;

		rip_update_fields( $post_id, $cs['fields'], 'group_rip_case_study.json' );
		$ids[ $slug ] = $post_id;
	}
	return $ids;
}

function rip_link_related_case_studies( $ids ) {
	$related_map = array(
		'alexis-delivery-service' => array( 'reyes-custom-millwork', 'dfw-flower-wall' ),
		'bella-med-spa'           => array( 'social-pro-photo-booth', 'dfw-flower-wall' ),
		'dfw-flower-wall'         => array( 'social-pro-photo-booth', 'bella-med-spa' ),
		'reyes-custom-millwork'   => array( 'turf-and-design', 'alexis-delivery-service' ),
		'social-pro-photo-booth'  => array( 'dfw-flower-wall', 'turf-and-design' ),
		'turf-and-design'         => array( 'reyes-custom-millwork', 'social-pro-photo-booth' ),
	);
	foreach ( $related_map as $slug => $related_slugs ) {
		if ( empty( $ids[ $slug ] ) ) continue;
		$related_ids = array();
		foreach ( $related_slugs as $rs ) {
			if ( ! empty( $ids[ $rs ] ) ) $related_ids[] = $ids[ $rs ];
		}
		rip_update_fields( $ids[ $slug ], array( 'related_case_studies' => $related_ids ), 'group_rip_case_study.json' );
	}
}

function rip_seed_construction() {
	$post_id = wp_insert_post( array(
		'post_type'   => 'rip_industry',
		'post_title'  => 'Construction SEO',
		'post_name'   => 'construction',
		'post_status' => 'draft', // nothing goes live on activation — publish deliberately from wp-admin
	) );
	if ( is_wp_error( $post_id ) || ! $post_id ) return;

	$fields = array(
		'industry_name' => 'Construction',
		'hero_eyebrow' => 'DALLAS CONSTRUCTION SEO',
		'hero_title' => "Your customers are Googling contractors. <em>You're on page two.</em>",
		'hero_sub' => 'We move Dallas general contractors, remodelers, and commercial builders from buried search results to the top of Google, then turn those clicks into booked jobs.',
		'hero_stat1_value' => '100+', 'hero_stat1_label' => 'Businesses Ranked',
		'hero_stat2_value' => '5 Star', 'hero_stat2_label' => 'Rated on Google',
		'hero_chips' => array(
			array( 'text' => 'Ranking #1 for "GC near me"' ),
			array( 'text' => '4.9★ rated on Google' ),
		),
		'hero_stat3_label' => '— Up to', 'hero_stat3_value' => '3.2<span>x</span>', 'hero_stat3_sub' => 'More leads booked',
		'hero_card_name' => 'Dallas GC Co.', 'hero_card_rating' => '★ 4.9 · 128 reviews', 'hero_card_badge' => '#1 on Google',
		'proof_eyebrow' => 'Why construction companies invest in SEO',
		'proof_title' => 'Homeowners and GCs Google <em>before they ever call.</em>',
		'proof_stats' => array(
			array( 'num' => '98', 'suffix' => '%', 'label' => 'of homeowners and commercial buyers search online before choosing a contractor.' ),
			array( 'num' => '76', 'suffix' => '%', 'label' => 'contact a contractor within 24 hours of finding them in search results.' ),
			array( 'num' => '2–5', 'suffix' => 'x', 'label' => 'average ROI for construction companies that invest in SEO consistently.' ),
		),
		'segments_title' => "Whoever's doing the job, <em>we rank you for it.</em>",
		'segments' => array(
			array( 'icon' => 'hard-hat', 'title' => 'General Contractors', 'text' => 'Multi-project pipelines and repeat-client search behavior across every job you take on.', 'keywords' => '"general contractor near me," "custom home builder Dallas"' ),
			array( 'icon' => 'home', 'title' => 'Remodelers & Custom Home Builders', 'text' => 'High-intent renovation searches with long research cycles before a homeowner ever calls.', 'keywords' => '"kitchen remodel Dallas," "home addition contractor"' ),
			array( 'icon' => 'building-2', 'title' => 'Commercial & Industrial Builders', 'text' => 'RFP-driven searches, with procurement teams researching bidders online before shortlisting.', 'keywords' => '"commercial construction company Dallas," "industrial contractor DFW"' ),
			array( 'icon' => 'wrench', 'title' => 'Specialty Subcontractors', 'text' => 'Concrete, framing, and rough-in trades competing on niche, highly local search terms.', 'keywords' => '"concrete contractor Dallas," "framing subcontractor DFW"' ),
		),
		'compare_title' => 'Why Construction Companies <em>Choose Ranked</em>',
		'compare_subhead' => 'for getting booked, not just ranked',
		'compare_rows' => array(
			array( 'label' => 'Leads Booked: in 30 days*', 'type' => 'metric', 'ranked_value' => '24%', 'agency_value' => '4%' ),
			array( 'label' => 'in 90 days*', 'type' => 'metric', 'ranked_value' => '41%', 'agency_value' => '9%' ),
			array( 'label' => 'One Contractor Per City, Per Trade', 'type' => 'check', 'ranked_value' => 'yes', 'agency_value' => 'no' ),
			array( 'label' => 'Dedicated Service-Area Pages', 'type' => 'check', 'ranked_value' => 'yes', 'agency_value' => 'no' ),
			array( 'label' => 'Reports Tied to Leads Booked', 'type' => 'check', 'ranked_value' => 'yes', 'agency_value' => 'no' ),
		),
		'compare_footnote' => "*Illustrative range based on active Ranked International construction clients' first 30–90 days, compared with typical results reported for generalist agencies without industry specialization.",
		'spotlight_eyebrow' => 'Client results',
		'spotlight_title' => 'Results Dallas businesses<br>actually talk about.',
		'spotlights' => array(
			array( 'kicker' => '01 — Bella Med Spa', 'quote' => "&ldquo;Page one in 60 days.<br>I stopped doubting them.&rdquo;", 'client' => 'Maria T. — Bella Med Spa, Dallas TX',
				'metric1_label' => 'Calls per month', 'metric1_value' => '600<span>+</span>', 'metric1_sub' => 'up from 40 · in 7 months',
				'metric2_label' => 'Time to page one', 'metric2_value' => '60<span>d</span>', 'metric2_sub' => 'from near-zero organic traffic' ),
			array( 'kicker' => '02 — North TX HVAC', 'quote' => '&ldquo;40% more calls.<br>Same ad budget.&rdquo;', 'client' => 'James R. — North TX HVAC, Dallas TX',
				'metric1_label' => 'Booked calls', 'metric1_value' => '612', 'metric1_sub' => 'in 7 months · same ad spend',
				'metric2_label' => 'Call volume lift', 'metric2_value' => '40<span>%</span>', 'metric2_sub' => 'more calls, zero extra budget' ),
			array( 'kicker' => '03 — Lone Star Roofing', 'quote' => '&ldquo;Best ROI of any marketing<br>we&rsquo;ve ever done.&rdquo;', 'client' => 'Derek S. — Lone Star Roofing, Dallas TX',
				'metric1_label' => 'SERP position', 'metric1_value' => '#1', 'metric1_sub' => 'for "roofer Dallas" keywords',
				'metric2_label' => 'Businesses ranked', 'metric2_value' => '100<span>+</span>', 'metric2_sub' => 'local businesses across DFW' ),
		),
		'services_title' => 'Every Service Under One Roof For Your Trade',
		'services_center' => 'Get You Ranked',
		'services' => array(
			array( 'icon' => 'map-pin', 'title' => 'Local SEO', 'text' => 'Rank for every city and county you build in, not just your home base.' ),
			array( 'icon' => 'settings-2', 'title' => 'Technical SEO', 'text' => 'Fast, crawlable sites that turn more visitors into calls and quote requests.' ),
			array( 'icon' => 'file-text', 'title' => 'Content', 'text' => 'Project galleries and case studies that build trust before the first call.' ),
			array( 'icon' => 'link-2', 'title' => 'Link Building', 'text' => 'Backlinks from trade publications and associations that lift domain authority.' ),
			array( 'icon' => 'mouse-pointer-click', 'title' => 'Google Ads', 'text' => 'High-intent campaigns tailored to commercial and emergency-repair leads.' ),
			array( 'icon' => 'map-pin', 'title' => 'Google Business Profile', 'text' => 'Project photos and reviews that turn lookers into booked jobs.' ),
		),
		'process_eyebrow' => 'How it works',
		'process_steps' => array(
			array( 'icon' => 'search', 'title' => 'Free audit', 'desc' => "I show you the 3 keywords you're losing to competitors right now." ),
			array( 'icon' => 'calendar-check', 'title' => '90-day service-area roadmap', 'desc' => 'A focused plan for every city and project type you build in.' ),
			array( 'icon' => 'trending-up', 'title' => 'Build & rank service-area pages', 'desc' => 'We do the work — technical, content, local, links — and get you found.' ),
			array( 'icon' => 'bar-chart-2', 'title' => 'Report leads booked', 'desc' => 'Every month, one number that matters: jobs in your calendar.' ),
		),
		'faq_title' => 'FAQ\'s: What Every Construction Company Should Know',
		'faq_sub' => "Curious about how SEO works for general contractors and builders? We've answered the most common questions to help you understand how Ranked International can support your growth.",
		'faqs' => array(
			array( 'question' => 'How does SEO work for general contractors with long sales cycles?', 'answer' => "We target the searches homeowners and commercial buyers run months before they sign a contract — so by the time they're ready to hire, you're already the name they recognize. Most campaigns show directional movement in 60–90 days." ),
			array( 'question' => 'Can you build separate pages for every city and county we serve?', 'answer' => 'Yes. Dedicated, optimized service-area pages are one of the highest-leverage moves for construction companies that work jobs across multiple cities or counties.' ),
			array( 'question' => 'Do you work with competing contractors in my market?', 'answer' => 'No. We take one client per trade, per city, so we are never optimizing your competitor against you.' ),
			array( 'question' => 'Should I invest in SEO or PPC ahead of my busy season?', 'answer' => 'PPC can create faster lead flow going into a busy season, while SEO builds durable, compounding visibility. Most construction companies use both together — PPC for immediate volume, SEO for the long game.' ),
		),
		'cta_title' => "Let's get your phone <em>ringing</em>.",
		'cta_sub' => 'Get a free audit showing the 3 keywords your competitors are taking customers from right now.',
	);

	$logo_ids = array_map( 'rip_seed_attachment', array( 'brand-logos/10.png', 'brand-logos/6.png', 'brand-logos/8.png', 'brand-logos/9.png', 'brand-logos/11.png', 'brand-logos/7.png' ) );
	$trusted = array();
	foreach ( array_filter( $logo_ids ) as $lid ) $trusted[] = array( 'logo' => $lid );
	$fields['trusted_logos'] = $trusted;

	$poster_id = rip_seed_attachment( 'construction/hero-visual.jpg' );
	if ( $poster_id ) $fields['hero_video_poster'] = $poster_id;

	$video_id = rip_seed_attachment( 'construction/hero-visual.mp4' );
	if ( $video_id ) $fields['hero_video'] = $video_id;

	$banner_id = rip_seed_attachment( 'construction/segments-band.jpg' );
	if ( $banner_id ) $fields['segments_banner'] = $banner_id;

	rip_update_fields( $post_id, $fields, 'group_rip_industry_page.json' );
}
