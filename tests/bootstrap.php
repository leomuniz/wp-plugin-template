<?php
/**
 * PHPUnit bootstrap file.
 *
 * Loads the Composer autoloader and defines WordPress/plugin constants
 * so that source files pass their guard clauses without loading WordPress.
 * BrainMonkey handles WordPress function mocking.
 *
 * @package WPPluginTemplate
 */

// Define ABSPATH so source files pass the `defined( 'ABSPATH' ) || exit;` guard.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() . '/' );
}

// Define plugin constants so source files can reference them.
define( 'WP_PLUGIN_TEMPLATE_VERSION', '1.0.0-test' );
define( 'WP_PLUGIN_TEMPLATE_FILE', dirname( __DIR__ ) . '/wp-plugin-template.php' );
define( 'WP_PLUGIN_TEMPLATE_DIR', dirname( __DIR__ ) . '/' );
define( 'WP_PLUGIN_TEMPLATE_URL', 'https://example.com/wp-content/plugins/wp-plugin-template/' );

// Load Composer autoloader (must come after constants for Patchwork compatibility).
require_once dirname( __DIR__ ) . '/vendor/autoload.php';
