<?php

namespace Eugene\ApiPlugin;

class Plugin {
	private static $instance = null;

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
			self::$instance->init_hooks();
		}

		return self::$instance;
	}

	private function init_hooks() {
		add_action( 'wp_ajax_nopriv_eugene_fetch_data', [ $this, 'handle_ajax_request' ] );
		add_action( 'wp_ajax_eugene_fetch_data', [ $this, 'handle_ajax_request' ] );
		add_shortcode( 'eugene_data_display', [ $this, 'display_data_shortcode' ] );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'eugene_refresh_data', [ $this, 'cli_force_refresh' ] );
		}
	}

	public function handle_ajax_request() {
		check_ajax_referer( 'eugene_api_nonce', 'nonce' );

		$data = get_transient( 'eugene_api_data' );
		if ( false === $data ) {
			$response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
			$data     = wp_remote_retrieve_body( $response );
			set_transient( 'eugene_api_data', $data, HOUR_IN_SECONDS );
		}

		wp_send_json_success( $data );
	}

	public function cli_force_refresh() {
		delete_transient( 'eugene_api_data' );
		WP_CLI::success( 'Data cache cleared. It will be refreshed on the next AJAX request.' );
	}
}
