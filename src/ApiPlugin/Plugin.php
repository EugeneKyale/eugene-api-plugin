<?php

namespace Eugene\ApiPlugin;

/**
 * Main plugin initialization class.
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Singleton instance of this class.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Retrieves the singleton instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin Returns the instance of this class.
	 */
	public static function instance(): Plugin {
		if ( self::$instance === null ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin by setting up the necessary hooks and loaders.
	 *
	 * @since 1.0.0
	 */
	private function init(): void {
		Ajax\Handler::register();
		Admin\ApiData::register();
		Blocks\ApiData::register();
		WPCLI\ForceRefresh::register();
	}
}
