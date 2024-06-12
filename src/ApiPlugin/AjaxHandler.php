<?php

namespace Eugene\ApiPlugin;

/**
 * Handles all AJAX operations for the plugin.
 *
 * @since 1.0.0
 */
class AjaxHandler {
	/**
	 * API URL used for fetching data.
	 *
	 * @since 1.0.0
	 */
	protected static $apiUrl = 'https://miusage.com/v1/challenge/1/';

	/**
	 * Register AJAX hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public static function register() {
		add_action( 'wp_ajax_nopriv_eugene_fetch_data', [ self::class, 'handle_request' ] );
		add_action( 'wp_ajax_eugene_fetch_data', [ self::class, 'handle_request' ] );
		add_action( 'rest_api_init', [ self::class, 'register_api_routes' ] );
	}

	/**
	 * Fetch data from the external API on plugin activation and cache it.
	 *
	 * @since 1.0.0
	 */
	public static function fetch_and_cache_data() {
		$response = wp_remote_get( self::$apiUrl );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			error_log( 'Failed to fetch initial data' );

			return;
		}

		$data = wp_remote_retrieve_body( $response );
		if ( ! empty( $data ) ) {
			set_transient( 'eugene_api_data', $data, 3600 );
		}
	}

	/**
	 * Handle the AJAX request to fetch data from the external API.
	 *
	 * @since 1.0.0
	 */
	public static function handle_request() {
		check_ajax_referer( 'eugene_api_nonce', 'nonce' );

		// Fetch new data regardless of existing transient
		$response = wp_remote_get( self::$apiUrl );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			wp_send_json_error( 'Failed to fetch data' );

			return;
		}

		$data = wp_remote_retrieve_body( $response );
		set_transient( 'eugene_api_data', $data, 3600 );

		wp_send_json_success( json_decode( $data, true ) );
	}

	/**
	 * Fetch and return data for the REST API.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response The API response.
	 */
	public static function get_api_data() {
		// Check if data is cached.
		$data = get_transient( 'eugene_api_data' );

		if ( ! $data ) {
			// Fetch data if not cached.
			$response = wp_remote_get( self::$apiUrl );
			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return new \WP_REST_Response( [
					'message' => 'Failed to fetch data from external API',
					'error'   => $response->get_error_message(),
				], 500 );
			}

			$data = wp_remote_retrieve_body( $response );
			set_transient( 'eugene_api_data', $data, 3600 );
		}

		return new \WP_REST_Response( json_decode( $data, true ), 200 );
	}

	/**
	 * Register REST API routes.
	 *
	 * @since 1.0.0
	 */
	public static function register_api_routes() {
		register_rest_route( 'eugene/v1', '/data/', [
			'methods'             => 'GET',
			'callback'            => [ self::class, 'get_api_data' ],
			'permission_callback' => '__return_true'
		] );
	}
}
