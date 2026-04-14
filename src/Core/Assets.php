<?php
/**
 * Asset management class.
 *
 * @package WPPluginTemplate
 */

namespace WPPluginTemplate\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Handles enqueuing of plugin scripts and styles.
 */
class Assets {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public function enqueue_frontend() {
		wp_enqueue_style(
			'wp-plugin-template-main',
			WP_PLUGIN_TEMPLATE_URL . 'assets/css/main.css',
			array(),
			WP_PLUGIN_TEMPLATE_VERSION
		);

		wp_enqueue_script(
			'wp-plugin-template-main',
			WP_PLUGIN_TEMPLATE_URL . 'assets/js/main.js',
			array(),
			WP_PLUGIN_TEMPLATE_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}
