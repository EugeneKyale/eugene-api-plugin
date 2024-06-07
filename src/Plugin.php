<?php

namespace Eugene\ApiPlugin;

/**
 * Main plugin class for handling the AJAX requests and WP CLI commands.
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Holds the singleton instance of this class.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Singleton pattern for getting the instance of the class.
	 *
	 * @since 1.0.0
	 * 
	 * @return Plugin Returns the instance of this class.
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
			self::$instance->init_hooks();
		}

		return self::$instance;
	}

	/**
	 * Initializes WordPress hooks for the AJAX and WP CLI functionality.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_nopriv_eugene_fetch_data', [ $this, 'handle_ajax_request' ] );
		add_action( 'wp_ajax_eugene_fetch_data', [ $this, 'handle_ajax_request' ] );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'eugene_refresh_data', [ $this, 'cli_force_refresh' ] );
		}
	}

	/**
	 * Handles AJAX requests to fetch or retrieve cached data.
	 * This method first checks for cached data. If not found or expired, it fetches new data from the remote API,
	 * caches it, and returns the data.
	 *
	 * @since 1.0.0
	 */
	public function handle_ajax_request() {
		// Nonce check for additional security if needed (uncomment if required)
		// check_ajax_referer('eugene_api_nonce', 'nonce');

		$data = get_transient( 'eugene_api_data' );
		if ( false === $data ) {
			$response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
			if ( is_wp_error( $response ) ) {
				wp_send_json_error( 'Failed to fetch data' );

				return;
			}

			$data = wp_remote_retrieve_body( $response );
			set_transient( 'eugene_api_data', $data, 3600 ); // Cache for 1 hour.
		}

		wp_send_json_success( json_decode( $data ) );
	}

	/**
	 * WP CLI command to force refresh the data by clearing the cached data.
	 * This allows for immediate refresh of data upon next AJAX call, bypassing the one-hour limit.
	 *
	 * @since 1.0.0
	 */
	public function cli_force_refresh() {
		delete_transient( 'eugene_api_data' );
		\WP_CLI::success( 'Data cache cleared. It will be refreshed on the next AJAX request.' );
	}
}
