<?php

namespace Eugene\ApiPlugin\WPCLI;

/**
 * Handles WP CLI commands for the plugin.
 *
 * @since 1.0.0
 */
class ForceRefresh {
	/**
	 * Register WP CLI commands.
	 *
	 * @since 1.0.0
	 */
	public static function register() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'eugene_refresh_data', [ self::class, 'cli_force_refresh' ] );
		}
	}

	/**
	 * WP CLI command to force refresh the data by clearing the cached data.
	 * This allows for immediate refresh of data upon next AJAX call, bypassing the one-hour limit.
	 *
	 * @since 1.0.0
	 */
	public static function cli_force_refresh() {
		delete_transient( 'eugene_api_data' );
		\WP_CLI::success( 'Data cache cleared. It will be refreshed on the next AJAX request.' );
	}
}
