<?php
/**
 * Plugin Name: Eugene API Plugin
 * Description: Retrieves data from a remote API and exposes it via an AJAX endpoint, a Gutenberg block, and an admin page.
 * Version: 1.0
 * Author: Eugene Kyale
 * Author URI: https://eugenekyale.com/
 * Text Domain: eugene-api-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin paths and URLs.
define( 'EUGENE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EUGENE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include Composer autoloader if exists.
if ( file_exists( EUGENE_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	require_once EUGENE_PLUGIN_PATH . 'vendor/autoload.php';
}

// Initialize the plugin.
\Eugene\ApiPlugin\Plugin::instance();
