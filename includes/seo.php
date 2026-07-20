<?php
/** Yoast is the sole SEO renderer; ACF values are fallback inputs only. */
if ( ! defined( 'ABSPATH' ) ) exit;

function rip_is_neutral_homepage() {
	return is_page() && get_page_template_slug() === 'templates/template-home.php';
}

function rip_neutral_home_seo_title( $title = '' ) {
	return rip_is_neutral_homepage() ? 'SEO Agency for Growing Businesses | Ranked International' : $title;
}

function rip_neutral_home_seo_description( $description = '' ) {
	return rip_is_neutral_homepage()
		? 'SEO, local search, and paid media strategies that help growing businesses reach page one and turn search traffic into booked jobs.'
		: $description;
}

add_filter( 'pre_get_document_title', 'rip_neutral_home_seo_title', 100 );
add_filter( 'wpseo_title', 'rip_neutral_home_seo_title', 100 );
add_filter( 'wpseo_opengraph_title', 'rip_neutral_home_seo_title', 100 );
add_filter( 'wpseo_twitter_title', 'rip_neutral_home_seo_title', 100 );
add_filter( 'wpseo_metadesc', 'rip_neutral_home_seo_description', 100 );
add_filter( 'wpseo_opengraph_desc', 'rip_neutral_home_seo_description', 100 );
add_filter( 'wpseo_twitter_description', 'rip_neutral_home_seo_description', 100 );

add_filter( 'wpseo_schema_graph', function ( $graph ) {
	if ( ! rip_is_neutral_homepage() ) return $graph;
	foreach ( $graph as &$piece ) {
		$types = isset( $piece['@type'] ) ? (array) $piece['@type'] : array();
		if ( in_array( 'WebPage', $types, true ) ) {
			$piece['name']        = rip_neutral_home_seo_title();
			$piece['description'] = rip_neutral_home_seo_description();
		}
	}
	unset( $piece );
	return $graph;
}, 100 );

add_filter( 'wpseo_schema_graph', function ( $graph ) {
	if ( ! is_singular( 'rip_city' ) || ! function_exists( 'get_field' ) ) return $graph;
	$post_id     = get_queried_object_id();
	$seo_title   = get_post_meta( $post_id, '_yoast_wpseo_title', true ) === '' ? get_field( 'seo_title', $post_id ) : '';
	$seo_summary = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true ) === '' ? get_field( 'seo_description', $post_id ) : '';
	if ( ! $seo_title && ! $seo_summary ) return $graph;
	foreach ( $graph as &$piece ) {
		$types = isset( $piece['@type'] ) ? (array) $piece['@type'] : array();
		if ( ! in_array( 'WebPage', $types, true ) ) continue;
		if ( $seo_title ) $piece['name'] = wp_strip_all_tags( $seo_title );
		if ( $seo_summary ) $piece['description'] = wp_strip_all_tags( $seo_summary );
	}
	unset( $piece );
	return $graph;
}, 90 );

function rip_yoast_fallback_value( $current, $acf_key, $yoast_key ) {
	if ( ! rip_is_our_template() || ! is_singular() ) return $current;
	$post_id = get_queried_object_id();
	if ( get_post_meta( $post_id, $yoast_key, true ) !== '' ) return $current;
	$fallback = function_exists( 'get_field' ) ? get_field( $acf_key, $post_id ) : '';
	return $fallback ? wp_strip_all_tags( $fallback ) : $current;
}

add_filter( 'wpseo_title', function ( $title ) {
	return rip_yoast_fallback_value( $title, 'seo_title', '_yoast_wpseo_title' );
} );
add_filter( 'wpseo_metadesc', function ( $description ) {
	return rip_yoast_fallback_value( $description, 'seo_description', '_yoast_wpseo_metadesc' );
} );
foreach ( array( 'wpseo_opengraph_title', 'wpseo_twitter_title' ) as $filter ) {
	add_filter( $filter, function ( $title ) {
		return rip_yoast_fallback_value( $title, 'seo_title', '_yoast_wpseo_title' );
	} );
}
foreach ( array( 'wpseo_opengraph_desc', 'wpseo_twitter_description' ) as $filter ) {
	add_filter( $filter, function ( $description ) {
		return rip_yoast_fallback_value( $description, 'seo_description', '_yoast_wpseo_metadesc' );
	} );
}

add_filter( 'wpseo_schema_graph', 'rip_extend_yoast_schema_graph', 20, 2 );
function rip_extend_yoast_schema_graph( $graph, $context ) {
	if ( ! is_singular( 'rip_service' ) || ! function_exists( 'get_field' ) ) return $graph;
	$post_id = get_queried_object_id();
	$url     = get_permalink( $post_id );
	$name    = get_field( 'service_name', $post_id ) ?: get_the_title( $post_id );
	$desc    = get_field( 'seo_description', $post_id ) ?: get_field( 'hero_summary', $post_id );
	$graph[] = array(
		'@type'       => 'Service',
		'@id'         => $url . '#service',
		'url'         => $url,
		'name'        => wp_strip_all_tags( $name ),
		'description' => wp_strip_all_tags( $desc ),
		'isPartOf'    => array( '@id' => $url . '#webpage' ),
		'provider'    => array( '@id' => home_url( '/#organization' ) ),
		'areaServed'  => wp_strip_all_tags( get_field( 'primary_market', $post_id ) ?: 'Dallas-Fort Worth, Texas' ),
	);
	$faqs = get_field( 'faqs', $post_id );
	if ( is_array( $faqs ) && $faqs ) {
		$graph[] = array(
			'@type'      => 'FAQPage',
			'@id'        => $url . '#faq',
			'url'        => $url . '#faq',
			'isPartOf'   => array( '@id' => $url . '#webpage' ),
			'mainEntity' => array_map( function ( $faq ) {
				return array(
					'@type' => 'Question',
					'name' => wp_strip_all_tags( $faq['question'] ?? '' ),
					'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $faq['answer'] ?? '' ) ),
				);
			}, $faqs ),
		);
	}
	return $graph;
}
