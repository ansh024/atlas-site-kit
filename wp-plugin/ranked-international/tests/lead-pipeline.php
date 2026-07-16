<?php
if ( ! defined( 'ABSPATH' ) ) exit( 1 );
if ( ! defined( 'RIP_RECAPTCHA_SECRET_KEY' ) ) define( 'RIP_RECAPTCHA_SECRET_KEY', 'local-test-only' );

function rip_test_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "Lead pipeline test failed: {$message}\n" );
		exit( 1 );
	}
}

$captcha_case = array();
add_filter( 'pre_http_request', function ( $pre, $args, $url ) use ( &$captcha_case ) {
	if ( strpos( $url, 'google.com/recaptcha/api/siteverify' ) === false ) return $pre;
	return array( 'headers' => array(), 'body' => wp_json_encode( $captcha_case ), 'response' => array( 'code' => 200, 'message' => 'OK' ), 'cookies' => array(), 'filename' => null );
}, 10, 3 );

$captcha_case = array( 'success' => true, 'action' => 'audit_lead', 'score' => 0.9, 'hostname' => 'localhost' );
rip_test_assert( rip_verify_recaptcha( 'remote-token' )['valid'], 'valid v3 result rejected' );
$captcha_case['score'] = 0.1;
rip_test_assert( ! rip_verify_recaptcha( 'remote-token' )['valid'], 'low score accepted' );
$captcha_case = array( 'success' => true, 'action' => 'login', 'score' => 0.9, 'hostname' => 'localhost' );
rip_test_assert( ! rip_verify_recaptcha( 'remote-token' )['valid'], 'wrong action accepted' );
$captcha_case['action'] = 'audit_lead'; $captcha_case['hostname'] = 'attacker.test';
rip_test_assert( ! rip_verify_recaptcha( 'remote-token' )['valid'], 'wrong hostname accepted' );

$_SERVER['REMOTE_ADDR'] = '203.0.113.55';
$rate_email = 'server-rate-' . wp_generate_uuid4() . '@example.test';
for ( $attempt = 0; $attempt < 20; $attempt++ ) {
	delete_transient( rip_rate_limit_key( '15m', $_SERVER['REMOTE_ADDR'] . '|' . strtolower( $rate_email ) ) );
	rip_test_assert( rip_check_rate_limit( $rate_email ) === 0, 'daily rate limit triggered too early' );
}
delete_transient( rip_rate_limit_key( '15m', $_SERVER['REMOTE_ADDR'] . '|' . strtolower( $rate_email ) ) );
rip_test_assert( rip_check_rate_limit( $rate_email ) === DAY_IN_SECONDS, 'daily rate limit did not trigger' );
delete_transient( rip_rate_limit_key( '15m', $_SERVER['REMOTE_ADDR'] . '|' . strtolower( $rate_email ) ) );
delete_transient( rip_rate_limit_key( 'day', $_SERVER['REMOTE_ADDR'] . '|' . strtolower( $rate_email ) ) );

$lead = array(
	'name' => 'Pipeline QA', 'email' => 'pipeline@example.test', 'phone' => '469-555-0100',
	'website' => 'https://example.test', 'service' => 'Local SEO', 'market' => 'Dallas, TX',
	'notes' => 'Automated test', 'page_url' => home_url( '/local-seo-services/' ),
);
$uuid = wp_generate_uuid4();
$post_id = rip_store_lead( $lead, 0.9, $uuid );
rip_test_assert( ! is_wp_error( $post_id ), 'lead was not stored' );
rip_test_assert( rip_find_lead_by_uuid( $uuid ) === $post_id, 'idempotency lookup failed' );
rip_test_assert( get_post_status( $post_id ) === 'private', 'lead is not private' );
rip_test_assert( get_post_meta( $post_id, '_rip_lead_recaptcha_token', true ) === '', 'CAPTCHA token was stored' );
rip_test_assert( get_post_meta( $post_id, '_rip_lead_ip', true ) === '', 'raw IP was stored' );

add_filter( 'pre_wp_mail', '__return_false' );
for ( $attempt = 0; $attempt < 6; $attempt++ ) rip_send_lead_email( $post_id );
remove_filter( 'pre_wp_mail', '__return_false' );
rip_test_assert( get_post_meta( $post_id, '_rip_lead_status', true ) === 'failed', 'terminal mail failure not recorded' );
rip_test_assert( (int) get_post_meta( $post_id, '_rip_lead_attempts', true ) === 6, 'retry attempt count is incorrect' );
rip_test_assert( ! wp_next_scheduled( 'rip_retry_audit_lead', array( $post_id ) ), 'terminal failure left a scheduled retry' );

wp_delete_post( $post_id, true );
echo "Lead pipeline server tests passed.\n";
