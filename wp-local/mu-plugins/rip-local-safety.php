<?php
/**
 * Local-only guard used by the disposable live-site replica.
 *
 * It is copied into wp-content/mu-plugins by wp:restore-live and is never
 * deployed with the Ranked International plugin.
 */

if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) || 'local' !== WP_ENVIRONMENT_TYPE ) {
	return;
}

add_filter(
	'pre_wp_mail',
	static function () {
		return true;
	},
	PHP_INT_MIN
);

add_filter(
	'pre_http_request',
	static function ( $preempt, $args, $url ) {
		$host = wp_parse_url( $url, PHP_URL_HOST );
		$local_hosts = array( 'localhost', '127.0.0.1', '::1', null );

		if ( in_array( $host, $local_hosts, true ) ) {
			return $preempt;
		}

		return new WP_Error(
			'rip_local_external_request_blocked',
			'External HTTP requests are disabled in the local live-site replica.'
		);
	},
	PHP_INT_MIN,
	3
);
