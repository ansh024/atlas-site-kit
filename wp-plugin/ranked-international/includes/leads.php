<?php
/** Durable, abuse-resistant audit lead capture and owner notification. */
if ( ! defined( 'ABSPATH' ) ) exit;

const RIP_LEAD_RETRY_DELAYS = array( 300, 1800, 7200, 43200, 86400 );

add_action( 'init', 'rip_register_lead_post_type' );
function rip_register_lead_post_type() {
	register_post_type( 'rip_audit_lead', array(
		'labels' => array( 'name' => 'Audit Leads', 'singular_name' => 'Audit Lead', 'menu_name' => 'Audit Leads' ),
		'public' => false, 'publicly_queryable' => false, 'exclude_from_search' => true,
		'show_ui' => true, 'show_in_menu' => true, 'show_in_rest' => false, 'has_archive' => false,
		'menu_icon' => 'dashicons-email-alt', 'supports' => array(),
		'capabilities' => array(
			'edit_post' => 'manage_options', 'read_post' => 'manage_options', 'delete_post' => 'manage_options',
			'edit_posts' => 'manage_options', 'edit_others_posts' => 'manage_options', 'publish_posts' => 'manage_options',
			'read_private_posts' => 'manage_options', 'delete_posts' => 'manage_options', 'create_posts' => 'do_not_allow',
		), 'map_meta_cap' => false,
	) );
}

function rip_lead_config( $name, $default = '' ) {
	$constant = 'RIP_' . strtoupper( $name );
	return defined( $constant ) ? constant( $constant ) : $default;
}

function rip_json_error( $code, $message, $status = 400, $extra = array() ) {
	wp_send_json_error( array_merge( array( 'code' => $code, 'message' => $message ), $extra ), $status );
}

function rip_rate_limit_key( $window, $identity ) {
	return 'rip_rl_' . $window . '_' . hash_hmac( 'sha256', strtolower( $identity ), wp_salt( 'nonce' ) );
}

function rip_check_rate_limit( $email ) {
	$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) );
	$identity = $ip . '|' . strtolower( $email );
	foreach ( array( array( '15m', 900, 5 ), array( 'day', DAY_IN_SECONDS, 20 ) ) as $rule ) {
		$key = rip_rate_limit_key( $rule[0], $identity );
		$count = (int) get_transient( $key );
		if ( $count >= $rule[2] ) return $rule[1];
		set_transient( $key, $count + 1, $rule[1] );
	}
	return 0;
}

function rip_verify_recaptcha( $token ) {
	$pre = apply_filters( 'rip_pre_verify_recaptcha', null, $token );
	if ( is_array( $pre ) ) return $pre;
	$secret = rip_lead_config( 'recaptcha_secret_key' );
	if ( ! $secret || ! $token ) return array( 'valid' => false, 'code' => 'captcha_unavailable' );
	$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
		'timeout' => 5,
		'body' => array( 'secret' => $secret, 'response' => $token ),
	) );
	if ( is_wp_error( $response ) ) return array( 'valid' => false, 'code' => 'captcha_unavailable' );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	$expected_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	$threshold = (float) rip_lead_config( 'recaptcha_score_threshold', 0.5 );
	$valid = ! empty( $data['success'] )
		&& ( $data['action'] ?? '' ) === 'audit_lead'
		&& (float) ( $data['score'] ?? 0 ) >= $threshold
		&& strtolower( $data['hostname'] ?? '' ) === strtolower( $expected_host );
	return array( 'valid' => $valid, 'code' => $valid ? '' : 'captcha_rejected', 'score' => (float) ( $data['score'] ?? 0 ) );
}

add_filter( 'rip_pre_verify_recaptcha', function ( $result, $token ) {
	if ( wp_get_environment_type() === 'local' && defined( 'RIP_RECAPTCHA_TEST_BYPASS' ) && RIP_RECAPTCHA_TEST_BYPASS && $token === 'local-test' ) {
		return array( 'valid' => true, 'code' => '', 'score' => 1.0 );
	}
	return $result;
}, 10, 2 );

function rip_lead_field_limits() {
	return array( 'name' => 120, 'email' => 190, 'phone' => 40, 'website' => 2048, 'market' => 160, 'service' => 160, 'notes' => 2000, 'page_url' => 2048 );
}

function rip_lead_request_too_long() {
	foreach ( rip_lead_field_limits() as $key => $limit ) {
		$value = (string) wp_unslash( $_POST[ $key ] ?? '' );
		$length = function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );
		if ( $length > $limit ) return $key;
	}
	return '';
}

function rip_clean_lead_request() {
	$limits = rip_lead_field_limits();
	$lead = array();
	foreach ( $limits as $key => $limit ) {
		$value = wp_unslash( $_POST[ $key ] ?? '' );
		$value = $key === 'notes' ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		$lead[ $key ] = function_exists( 'mb_substr' ) ? mb_substr( $value, 0, $limit ) : substr( $value, 0, $limit );
	}
	$lead['email'] = sanitize_email( $lead['email'] );
	$lead['website'] = esc_url_raw( $lead['website'], array( 'http', 'https' ) );
	$lead['page_url'] = esc_url_raw( $lead['page_url'], array( 'http', 'https' ) );
	return $lead;
}

function rip_validate_lead( $lead ) {
	foreach ( array( 'name', 'email', 'phone', 'website', 'market', 'page_url' ) as $field ) {
		if ( empty( $lead[ $field ] ) ) return 'missing_' . $field;
	}
	if ( ! is_email( $lead['email'] ) ) return 'invalid_email';
	$website = wp_parse_url( $lead['website'] );
	$page = wp_parse_url( $lead['page_url'] );
	if ( ! $website || ! in_array( $website['scheme'] ?? '', array( 'http', 'https' ), true ) || empty( $website['host'] ) ) return 'invalid_url';
	if ( ! $page || ! in_array( $page['scheme'] ?? '', array( 'http', 'https' ), true ) || empty( $page['host'] ) || strtolower( $page['host'] ) !== strtolower( wp_parse_url( home_url( '/' ), PHP_URL_HOST ) ) ) return 'invalid_page_url';
	if ( ! preg_match( '/^[0-9+().\-\s]{7,40}$/', $lead['phone'] ) ) return 'invalid_phone';
	return '';
}

function rip_store_lead( $lead, $score, $uuid ) {
	$post_id = wp_insert_post( array(
		'post_type' => 'rip_audit_lead', 'post_status' => 'private',
		'post_title' => $lead['name'] . ' — ' . $lead['email'], 'post_name' => $uuid,
	), true );
	if ( is_wp_error( $post_id ) ) return $post_id;
	foreach ( $lead as $key => $value ) update_post_meta( $post_id, '_rip_lead_' . $key, $value );
	update_post_meta( $post_id, '_rip_lead_uuid', $uuid );
	update_post_meta( $post_id, '_rip_lead_submitted_at', current_time( 'mysql', true ) );
	update_post_meta( $post_id, '_rip_lead_recaptcha_score', $score );
	update_post_meta( $post_id, '_rip_lead_status', 'pending' );
	update_post_meta( $post_id, '_rip_lead_attempts', 0 );
	return $post_id;
}

function rip_find_lead_by_uuid( $uuid ) {
	$ids = get_posts( array( 'post_type' => 'rip_audit_lead', 'post_status' => 'private', 'meta_key' => '_rip_lead_uuid', 'meta_value' => $uuid, 'fields' => 'ids', 'posts_per_page' => 1 ) );
	return $ids ? (int) $ids[0] : 0;
}

function rip_lead_response( $post_id ) {
	$status = get_post_meta( $post_id, '_rip_lead_status', true );
	$delivered = $status === 'delivered';
	wp_send_json_success( array(
		'status' => $delivered ? 'delivered' : 'delivery_pending',
		'message' => $delivered ? 'Audit request received.' : 'Your request is saved. Our notification is temporarily delayed.',
		'reference' => get_post_meta( $post_id, '_rip_lead_uuid', true ),
	), $delivered ? 200 : 202 );
}

function rip_lead_payload( $post_id ) {
	$keys = array( 'name', 'email', 'phone', 'website', 'service', 'market', 'notes', 'page_url' );
	$lead = array();
	foreach ( $keys as $key ) $lead[ $key ] = (string) get_post_meta( $post_id, '_rip_lead_' . $key, true );
	return $lead;
}

function rip_send_lead_email( $post_id ) {
	$lead = rip_lead_payload( $post_id );
	$attempts = (int) get_post_meta( $post_id, '_rip_lead_attempts', true ) + 1;
	update_post_meta( $post_id, '_rip_lead_attempts', $attempts );
	update_post_meta( $post_id, '_rip_lead_last_attempt', current_time( 'mysql', true ) );
	$to = rip_lead_config( 'audit_lead_recipient', get_option( 'admin_email' ) );
	$to = apply_filters( 'rip_audit_lead_recipient', $to, $lead );
	$subject = 'New free SEO audit request — ' . $lead['name'];
	$body = "Name: {$lead['name']}\nEmail: {$lead['email']}\nPhone: {$lead['phone']}\nWebsite: {$lead['website']}\nService: {$lead['service']}\nPrimary market: {$lead['market']}\nNotes: {$lead['notes']}\nSubmitted from: {$lead['page_url']}";
	$failed = null;
	$listener = function ( $error ) use ( &$failed ) { $failed = $error; };
	add_action( 'wp_mail_failed', $listener );
	$sent = wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $lead['name'] . ' <' . $lead['email'] . '>' ) );
	remove_action( 'wp_mail_failed', $listener );
	if ( $sent && ! $failed ) {
		wp_clear_scheduled_hook( 'rip_retry_audit_lead', array( $post_id ) );
		update_post_meta( $post_id, '_rip_lead_status', 'delivered' );
		$message_id = apply_filters( 'rip_audit_lead_message_id', '', $post_id, $lead );
		if ( $message_id ) update_post_meta( $post_id, '_rip_lead_message_id', sanitize_text_field( $message_id ) );
		delete_post_meta( $post_id, '_rip_lead_failure' );
		delete_post_meta( $post_id, '_rip_lead_next_retry' );
		do_action( 'rip_audit_lead_delivery_status', $post_id, 'delivered', $attempts );
		return true;
	}
	update_post_meta( $post_id, '_rip_lead_failure', $failed instanceof WP_Error ? sanitize_key( $failed->get_error_code() ) : 'mail_rejected' );
	rip_schedule_lead_retry( $post_id, $attempts );
	return false;
}

function rip_schedule_lead_retry( $post_id, $attempts ) {
	$index = $attempts - 1;
	if ( ! isset( RIP_LEAD_RETRY_DELAYS[ $index ] ) ) {
		wp_clear_scheduled_hook( 'rip_retry_audit_lead', array( $post_id ) );
		update_post_meta( $post_id, '_rip_lead_status', 'failed' );
		delete_post_meta( $post_id, '_rip_lead_next_retry' );
		do_action( 'rip_audit_lead_delivery_status', $post_id, 'failed', $attempts );
		return;
	}
	$when = time() + RIP_LEAD_RETRY_DELAYS[ $index ];
	update_post_meta( $post_id, '_rip_lead_status', 'retrying' );
	update_post_meta( $post_id, '_rip_lead_next_retry', gmdate( 'Y-m-d H:i:s', $when ) );
	if ( ! wp_next_scheduled( 'rip_retry_audit_lead', array( $post_id ) ) ) wp_schedule_single_event( $when, 'rip_retry_audit_lead', array( $post_id ) );
	do_action( 'rip_audit_lead_delivery_status', $post_id, 'retrying', $attempts );
}

add_action( 'rip_retry_audit_lead', function ( $post_id ) {
	$status = get_post_meta( $post_id, '_rip_lead_status', true );
	if ( get_post_type( $post_id ) !== 'rip_audit_lead' || in_array( $status, array( 'delivered', 'failed' ), true ) ) return;
	rip_send_lead_email( $post_id );
} );

add_action( 'wp_ajax_rankd_audit_lead', 'rip_handle_audit_lead' );
add_action( 'wp_ajax_nopriv_rankd_audit_lead', 'rip_handle_audit_lead' );
function rip_handle_audit_lead() {
	if ( ! check_ajax_referer( 'rip_audit_lead', 'nonce', false ) ) rip_json_error( 'invalid_nonce', 'Please refresh the page and try again.', 403 );
	$too_long = rip_lead_request_too_long();
	if ( $too_long ) rip_json_error( 'field_too_long', 'One or more fields are too long.', 422, array( 'field' => $too_long ) );
	$lead = rip_clean_lead_request();
	$request_id = sanitize_text_field( wp_unslash( $_POST['request_id'] ?? '' ) );
	if ( ! wp_is_uuid( $request_id ) ) rip_json_error( 'invalid_request_id', 'Please refresh the page and try again.', 422 );
	$existing = rip_find_lead_by_uuid( $request_id );
	if ( $existing ) rip_lead_response( $existing );
	$retry_after = rip_check_rate_limit( $lead['email'] );
	if ( $retry_after ) rip_json_error( 'rate_limited', 'Too many requests. Please try again later.', 429, array( 'retryAfter' => $retry_after ) );
	if ( ! empty( $_POST['company_fax'] ) ) rip_json_error( 'submission_rejected', 'We could not accept this request.', 400 );
	$validation = rip_validate_lead( $lead );
	if ( $validation ) rip_json_error( $validation, 'Please check the highlighted information and try again.', 422 );
	$captcha = rip_verify_recaptcha( sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ?? '' ) ) );
	if ( empty( $captcha['valid'] ) ) rip_json_error( $captcha['code'], 'Verification failed. Please try again.', 403 );
	$post_id = rip_store_lead( $lead, $captcha['score'] ?? 0, $request_id );
	if ( is_wp_error( $post_id ) ) rip_json_error( 'storage_failed', 'We could not safely save your request. Please call us.', 500 );
	do_action( 'rip_audit_lead_captured', $lead, $post_id );
	rip_send_lead_email( $post_id );
	rip_lead_response( $post_id );
}

add_filter( 'manage_rip_audit_lead_posts_columns', function ( $columns ) {
	return array( 'cb' => $columns['cb'], 'title' => 'Lead', 'email' => 'Email', 'source' => 'Source', 'delivery' => 'Delivery', 'attempts' => 'Attempts', 'date' => 'Submitted' );
} );
add_action( 'manage_rip_audit_lead_posts_custom_column', function ( $column, $post_id ) {
	if ( $column === 'email' ) echo esc_html( get_post_meta( $post_id, '_rip_lead_email', true ) );
	if ( $column === 'source' ) echo esc_html( get_post_meta( $post_id, '_rip_lead_page_url', true ) );
	if ( $column === 'delivery' ) echo '<strong>' . esc_html( get_post_meta( $post_id, '_rip_lead_status', true ) ) . '</strong>';
	if ( $column === 'attempts' ) echo esc_html( get_post_meta( $post_id, '_rip_lead_attempts', true ) );
}, 10, 2 );

add_action( 'add_meta_boxes_rip_audit_lead', function () {
	add_meta_box( 'rip-audit-lead-details', 'Submitted lead', function ( $post ) {
		$lead = rip_lead_payload( $post->ID );
		$rows = array(
			'Name' => $lead['name'], 'Email' => $lead['email'], 'Phone' => $lead['phone'],
			'Website' => $lead['website'], 'Service' => $lead['service'], 'Primary market' => $lead['market'],
			'Notes' => $lead['notes'], 'Source page' => $lead['page_url'],
			'Delivery status' => get_post_meta( $post->ID, '_rip_lead_status', true ),
			'Delivery attempts' => get_post_meta( $post->ID, '_rip_lead_attempts', true ),
			'Last attempt (UTC)' => get_post_meta( $post->ID, '_rip_lead_last_attempt', true ),
			'Next retry (UTC)' => get_post_meta( $post->ID, '_rip_lead_next_retry', true ),
			'Failure category' => get_post_meta( $post->ID, '_rip_lead_failure', true ),
			'Reference' => get_post_meta( $post->ID, '_rip_lead_uuid', true ),
		);
		echo '<table class="widefat striped"><tbody>';
		foreach ( $rows as $label => $value ) echo '<tr><th style="width:180px">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $value ) ) . '</td></tr>';
		echo '</tbody></table><p>This record is read-only. Correct lead details at the destination system rather than altering the submission record.</p>';
	}, 'rip_audit_lead', 'normal', 'high' );
} );

add_filter( 'post_row_actions', function ( $actions, $post ) {
	if ( $post->post_type !== 'rip_audit_lead' || ! current_user_can( 'manage_options' ) ) return $actions;
	if ( get_post_meta( $post->ID, '_rip_lead_status', true ) === 'delivered' ) return $actions;
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=rip_retry_lead&lead_id=' . $post->ID ), 'rip_retry_lead_' . $post->ID );
	$actions['rip_retry'] = '<a href="' . esc_url( $url ) . '">Retry notification</a>';
	return $actions;
}, 10, 2 );
add_action( 'admin_post_rip_retry_lead', function () {
	$post_id = absint( $_GET['lead_id'] ?? 0 );
	if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'rip_retry_lead_' . $post_id ) || get_post_type( $post_id ) !== 'rip_audit_lead' ) wp_die( 'Not allowed.' );
	update_post_meta( $post_id, '_rip_lead_attempts', 0 );
	update_post_meta( $post_id, '_rip_lead_status', 'pending' );
	rip_send_lead_email( $post_id );
	wp_safe_redirect( admin_url( 'edit.php?post_type=rip_audit_lead' ) ); exit;
} );

add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'rip_cleanup_audit_leads' ) ) wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'rip_cleanup_audit_leads' );
} );
add_action( 'rip_cleanup_audit_leads', function () {
	$days = max( 1, (int) apply_filters( 'rip_audit_lead_retention_days', 180 ) );
	$ids = get_posts( array( 'post_type' => 'rip_audit_lead', 'post_status' => 'private', 'posts_per_page' => 100, 'fields' => 'ids', 'date_query' => array( array( 'before' => gmdate( 'Y-m-d H:i:s', time() - DAY_IN_SECONDS * $days ) ) ) ) );
	foreach ( $ids as $id ) wp_delete_post( $id, true );
} );

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) ) return;
	if ( ! rip_lead_config( 'recaptcha_site_key' ) || ! rip_lead_config( 'recaptcha_secret_key' ) ) echo '<div class="notice notice-error"><p><strong>Ranked audit forms:</strong> reCAPTCHA v3 is not configured; production submissions fail closed.</p></div>';
	$failed_ids = get_posts( array( 'post_type' => 'rip_audit_lead', 'post_status' => 'private', 'meta_key' => '_rip_lead_status', 'meta_value' => 'failed', 'fields' => 'ids', 'posts_per_page' => 1 ) );
	if ( $failed_ids ) echo '<div class="notice notice-error"><p><strong>Ranked audit forms:</strong> an owner notification exhausted all retries. Review Audit Leads.</p></div>';
} );
