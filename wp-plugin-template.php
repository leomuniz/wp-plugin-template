<?php
/**
 * Plugin Name: WP Plugin Template
 * Plugin URI:  https://example.com/wp-plugin-template
 * Description: A WordPress plugin template for rapid plugin development.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: wp-plugin-template
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package WPPluginTemplate
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_PLUGIN_TEMPLATE_VERSION', '1.0.0' );
define( 'WP_PLUGIN_TEMPLATE_FILE', __FILE__ );
define( 'WP_PLUGIN_TEMPLATE_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_PLUGIN_TEMPLATE_URL', plugin_dir_url( __FILE__ ) );

// Composer autoloader.
if ( file_exists( WP_PLUGIN_TEMPLATE_DIR . 'vendor/autoload.php' ) ) {
	require_once WP_PLUGIN_TEMPLATE_DIR . 'vendor/autoload.php';
}

// Boot the plugin.
WPPluginTemplate\Core\Plugin::instance();
