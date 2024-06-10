<?php

namespace Eugene\ApiPlugin;

/**
 * Handles all Gutenberg block registration and scripts.
 *
 * @since 1.0.0
 */
class GutenbergBlock {
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
		// Register the block editor script
		wp_register_script(
			'eugene-block-editor',
			plugins_url( '../build/index.js', __FILE__ ),
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-data'
			],
			'1.0.0'
		);

		// Register frontend and editor styles.
		wp_register_style(
			'eugene-block-style',
			plugins_url( '../build/index.css', __FILE__ ),
			[],
			'1.0.0'
		);

		// Register the block.
		register_block_type( 'eugene/api-data-block', [
			'editor_script' => 'eugene-block-editor',
			'style'         => 'eugene-block-style',
		] );
	}
}
