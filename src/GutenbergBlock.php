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
		wp_register_script(
			'eugene-api-block-script',
			plugins_url( 'build/index.js', EUGENE_PLUGIN_PATH ),
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor'
			],
			filemtime( EUGENE_PLUGIN_PATH . 'build/index.js' )
		);

		register_block_type( 'eugene/api-plugin-block', [
			'editor_script' => 'eugene-api-block-script',
		] );
	}
}
