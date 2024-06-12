<?php

namespace Eugene\ApiPlugin\Blocks;

/**
 * Handles all Gutenberg block registration and scripts.
 *
 * @since 1.0.0
 */
class ApiData {
	/**
	 * Register the Gutenberg block and associated assets.
	 *
	 * @since 1.0.0
	 */
	public static function register(): void {
		add_action( 'init', [ self::class, 'register_block' ] );
	}

	/**
	 * Register the block and associated assets.
	 *
	 * @since 1.0.0
	 */
	public static function register_block(): void {
		$plugin_url = trailingslashit( EUGENE_PLUGIN_URL );

		// Register the block editor script.
		wp_register_script(
			'eugene-block-editor',
			$plugin_url . 'build/blocks/index.js',
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-data',
				'wp-components',
				'wp-editor',
			],
			'1.0.0'
		);

		// Register the block.
		register_block_type( 'eugene/api-data-block', [
			'editor_script'   => 'eugene-block-editor',
			'render_callback' => [ self::class, 'render_block' ],
			'attributes'      => [
				'showId'        => [ 'type' => 'boolean', 'default' => true ],
				'showFirstName' => [ 'type' => 'boolean', 'default' => true ],
				'showLastName'  => [ 'type' => 'boolean', 'default' => true ],
				'showEmail'     => [ 'type' => 'boolean', 'default' => true ],
				'showDate'      => [ 'type' => 'boolean', 'default' => true ],
			],
		] );
	}

	/**
	 * Server-side rendering for the block.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attributes Attributes for the block.
	 *
	 * @return string HTML content for the block.
	 */
	public static function render_block( array $attributes ): string {
		// Fetch data from the same source as the editor.
		$api_response = get_transient( 'eugene_api_data' );

		if ( ! $api_response ) {
			$response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return '<p>' . __( 'Failed to fetch data from external API', 'eugene-api-plugin' ) . '</p>';
			}
			$api_response = wp_remote_retrieve_body( $response );
			set_transient( 'eugene_api_data', $api_response, 3600 );
		}

		$data = json_decode( $api_response, true );

		return render_api_data_block_template( $attributes, $data );
	}
}

require_once EUGENE_PLUGIN_PATH . 'src/views/blocks/api-data.php';
