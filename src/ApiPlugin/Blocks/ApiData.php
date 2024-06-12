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
	public static function register() {
		add_action( 'init', [ self::class, 'register_block' ] );
	}

	/**
	 * Register the block and associated assets.
	 *
	 * @since 1.0.0
	 */
	public static function register_block() {
		// Register the block editor script.
		wp_register_script(
			'eugene-block-editor',
			plugins_url( '../../../build/blocks/index.js', __FILE__ ),
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

		// Register frontend and editor styles.
		wp_register_style(
			'eugene-block-style',
			plugins_url( '../../../build/blocks/index.css', __FILE__ ),
			[],
			'1.0.0'
		);

		// Register the block script for frontend.
		wp_register_script(
			'eugene-block-frontend',
			plugins_url( '../../../build/blocks/frontend.js', __FILE__ ),
			[],
			'1.0.0',
			true
		);

		// Register the block.
		register_block_type( 'eugene/api-data-block', [
			'editor_script'   => 'eugene-block-editor',
			'editor_style'    => 'eugene-block-style',
			'script'          => 'eugene-block-frontend',
			'style'           => 'eugene-block-style',
			'render_callback' => [ self::class, 'render_block' ],
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
	public static function render_block( $attributes ) {
		// Fetch data from the same source as the editor.
		$api_response = get_transient( 'eugene_api_data' );

		if ( ! $api_response ) {
			$api_response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
			set_transient( 'eugene_api_data', $api_response, 3600 );
		}

		$data = json_decode( wp_remote_retrieve_body( $api_response ), true );

		// Start output buffering to build the HTML string.
		ob_start();
		if ( isset( $data['data'] ) ) {
			echo '<div>';
			echo '<h4>' . esc_html( $data['title'] ) . '</h4>';
			echo '<table>';
			// Headers
			echo '<thead><tr>';
			foreach ( $data['data']['headers'] as $header ) {
				if ( isset( $attributes[ "show" . str_replace( ' ', '', $header ) ] ) && $attributes[ "show" . str_replace( ' ', '', $header ) ] ) {
					echo '<th>' . esc_html( $header ) . '</th>';
				}
			}
			echo '</tr></thead>';
			// Rows
			echo '<tbody>';
			foreach ( $data['data']['rows'] as $row ) {
				echo '<tr>';
				foreach ( $row as $key => $value ) {
					$keyFormatted = ucfirst( $key );
					if ( isset( $attributes["show$keyFormatted"] ) && $attributes["show$keyFormatted"] ) {
						echo '<td>' . esc_html( $value ) . '</td>';
					}
				}
				echo '</tr>';
			}
			echo '</tbody></table>';
			echo '</div>';
		}
		$output = ob_get_clean();

		return $output;
	}
}
