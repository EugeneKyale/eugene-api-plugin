<?php

namespace Eugene\ApiPlugin;

/**
 * Handles all AJAX operations for the plugin.
 *
 * @since 1.0.0
 */
class AjaxHandler {
	/**
	 * Register AJAX hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public static function register() {
		add_action( 'wp_ajax_nopriv_eugene_fetch_data', [ self::class, 'handle_request' ] );
		add_action( 'wp_ajax_eugene_fetch_data', [ self::class, 'handle_request' ] );
	}

	/**
	 * Handle the AJAX request to fetch data from the external API.
	 *
	 * @since 1.0.0
	 */
	public static function handle_request() {
		// Nonce check for additional security (Optional if your data retrieval doesn't involve sensitive actions)
		// check_ajax_referer('eugene_api_nonce', 'nonce');

		$data = get_transient( 'eugene_api_data' );
		if ( false === $data ) {
			$response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_send_json_error( 'Failed to fetch data' );

				return;
			}

			$data = wp_remote_retrieve_body( $response );
			set_transient( 'eugene_api_data', $data, 3600 );  // Cache for 1 hour.
		}

		wp_send_json_success( json_decode( $data, true ) );
	}
}
