<?php
/**
 * Main plugin class.
 *
 * @package WPPluginTemplate
 */

namespace WPPluginTemplate\Core;

use WPPluginTemplate\Admin\Admin;
use WPPluginTemplate\Frontend\Frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin singleton class that bootstraps the entire plugin.
 */
class Plugin {

	/**
	 * Single instance of the class.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor. Private to enforce singleton pattern.
	 */
	private function __construct() {
		$this->register_hooks();
		$this->init_components();
	}

	/**
	 * Register plugin lifecycle hooks.
	 */
	private function register_hooks() {
		register_activation_hook( WP_PLUGIN_TEMPLATE_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WP_PLUGIN_TEMPLATE_FILE, array( $this, 'deactivate' ) );

		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Initialize plugin components.
	 */
	private function init_components() {
		new Assets();
		new Admin();
		new Frontend();
	}

	/**
	 * Load plugin text domain for translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'wp-plugin-template',
			false,
			dirname( plugin_basename( WP_PLUGIN_TEMPLATE_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register Gutenberg blocks.
	 */
	public function register_blocks() {
		if ( file_exists( WP_PLUGIN_TEMPLATE_DIR . 'build/blocks/sample-block' ) ) {
			register_block_type( WP_PLUGIN_TEMPLATE_DIR . 'build/blocks/sample-block' );
		}
	}

	/**
	 * Plugin activation callback.
	 */
	public function activate() {
		// Add activation logic here (e.g., create database tables, set default options).
	}

	/**
	 * Plugin deactivation callback.
	 */
	public function deactivate() {
		// Add deactivation logic here (e.g., cleanup scheduled events).
	}
}
