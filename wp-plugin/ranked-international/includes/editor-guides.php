<?php
/** Visual page maps shown only inside the ACF editing screens. */

if ( ! defined( 'ABSPATH' ) ) exit;

function rip_editor_guide_configs() {
	return array(
		'field_rip_city_intro_note' => array(
			'image'   => 'home.jpg',
			'caption' => 'City Pages use this shared page layout. Numbered labels match the ACF tabs below.',
			'markers' => array(
				array( 4, '1', 'City & hero' ),
				array( 29, '2', 'Case-study text' ),
				array( 46, '3', 'Metric notes' ),
				array( 58, '4', 'Reviews' ),
				array( 79, '5', 'FAQs' ),
			),
		),
		'field_rip_ind_start_note' => array(
			'image'   => 'industry.jpg',
			'caption' => 'Industry Page reference. Some animated sections may appear simplified in this static guide.',
			'markers' => array(
				array( 7, '1', 'Hero & proof' ),
				array( 26, '2', 'Trust & audience' ),
				array( 48, '3', 'Comparison & spotlight' ),
				array( 70, '4', 'Services, process & FAQs' ),
			),
		),
		'field_rip_svc_start_note' => array(
			'image'   => 'service.jpg',
			'caption' => 'Service Page reference. The numbered labels follow the ACF tabs below.',
			'markers' => array(
				array( 4, '1', 'Identity & hero' ),
				array( 25, '2', 'Problems & workstreams' ),
				array( 55, '3', 'Proof & process' ),
				array( 82, '4', 'Fit, FAQs & CTA' ),
			),
		),
		'field_rip_cs_start_note' => array(
			'image'   => 'case-study.jpg',
			'caption' => 'Case Study reference. Optional sections appear only when their rows are filled in.',
			'markers' => array(
				array( 7, '1', 'Client, hero & chart' ),
				array( 33, '2', 'Strategy' ),
				array( 54, '3', 'Results & keywords' ),
				array( 72, '4', 'FAQs' ),
				array( 82, '5', 'Related pages & SEO' ),
			),
		),
		'field_rip_page_faq_start_note' => array(
			'image'   => 'home.jpg',
			'caption' => 'The FAQ editor controls the FAQ section near the bottom of this page.',
			'markers' => array(
				array( 79, 'FAQ', 'FAQ section' ),
			),
		),
	);
}

function rip_prepare_editor_guide_field( $field ) {
	$configs = rip_editor_guide_configs();
	if ( empty( $configs[ $field['key'] ] ) ) return $field;

	$config  = $configs[ $field['key'] ];
	if ( $field['key'] === 'field_rip_page_faq_start_note' ) {
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
		if ( $post_id && get_page_template_slug( $post_id ) === 'templates/template-turf-tree-service.php' ) {
			$config['image']   = 'turf.jpg';
			$config['caption'] = 'Turf & Tree Service reference. The FAQ section sits immediately above the final green call-to-action.';
			$config['markers'] = array( array( 77, 'FAQ', 'FAQ section' ) );
		}
	}
	$markers = '';
	foreach ( $config['markers'] as $marker ) {
		$markers .= sprintf(
			'<span class="rip-editor-guide__marker" style="top:%1$s%%"><b>%2$s</b><span>%3$s</span></span>',
			esc_attr( $marker[0] ),
			esc_html( $marker[1] ),
			esc_html( $marker[2] )
		);
	}

	$guide = sprintf(
		'<details class="rip-editor-guide"><summary>Open annotated visual page map</summary><p>%1$s</p><div class="rip-editor-guide__canvas"><img src="%2$s" alt="Visual reference for this page layout">%3$s</div></details>',
		esc_html( $config['caption'] ),
		esc_url( RIP_URL . 'assets/images/editor-guides/' . $config['image'] ),
		$markers
	);
	$field['message'] = ( $field['message'] ?? '' ) . $guide;
	return $field;
}

foreach ( array_keys( rip_editor_guide_configs() ) as $field_key ) {
	add_filter( 'acf/prepare_field/key=' . $field_key, 'rip_prepare_editor_guide_field' );
}

add_action( 'admin_head', function () {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->post_type, array( 'page', 'rip_city', 'rip_industry', 'rip_service', 'rip_case_study' ), true ) ) return;
	?>
	<style>
	.rip-editor-guide{margin-top:14px;padding:12px 14px;border:1px solid #c3c4c7;border-radius:6px;background:#fff}
	.rip-editor-guide>summary{cursor:pointer;color:#135e96;font-weight:600}
	.rip-editor-guide>p{margin:12px 0;color:#50575e}
	.rip-editor-guide__canvas{position:relative;width:min(720px,100%);overflow:hidden;border:1px solid #dcdcde;border-radius:4px;background:#f6f7f7}
	.rip-editor-guide__canvas img{display:block;width:100%;height:auto}
	.rip-editor-guide__marker{position:absolute;left:8px;display:flex;align-items:center;gap:7px;max-width:calc(100% - 16px);padding:5px 8px 5px 5px;border-radius:999px;background:rgba(10,21,41,.92);box-shadow:0 2px 8px rgba(0,0,0,.28);color:#fff;font-size:12px;line-height:1.2;transform:translateY(-50%)}
	.rip-editor-guide__marker b{display:grid;place-items:center;min-width:25px;height:25px;padding:0 6px;border-radius:999px;background:#caff00;color:#0a1529;font-size:11px}
	</style>
	<?php
} );
