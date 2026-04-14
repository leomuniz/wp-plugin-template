<?php
/**
 * Admin functionality.
 *
 * @package WPPluginTemplate
 */

namespace WPPluginTemplate\Admin;

use WPPluginTemplate\Core\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the plugin admin settings page.
 */
class Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add the plugin settings page under the Settings menu.
	 */
	public function add_menu_page() {
		add_options_page(
			__( 'WP Plugin Template', 'wp-plugin-template' ),
			__( 'WP Plugin Template', 'wp-plugin-template' ),
			'manage_options',
			'wp-plugin-template',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings and fields.
	 */
	public function register_settings() {
		register_setting(
			'wp_plugin_template_settings',
			'wp_plugin_template_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_options' ),
				'default'           => array(),
			)
		);

		add_settings_section(
			'wp_plugin_template_general',
			__( 'General Settings', 'wp-plugin-template' ),
			'__return_null',
			'wp-plugin-template'
		);

		add_settings_field(
			'sample_text',
			__( 'Sample Text Field', 'wp-plugin-template' ),
			array( $this, 'render_sample_text_field' ),
			'wp-plugin-template',
			'wp_plugin_template_general'
		);
	}

	/**
	 * Sanitize the plugin options.
	 *
	 * @param array $input Raw option values.
	 * @return array Sanitized option values.
	 */
	public function sanitize_options( $input ) {
		$sanitized = array();

		if ( isset( $input['sample_text'] ) ) {
			$sanitized['sample_text'] = sanitize_text_field( $input['sample_text'] );
		}

		return $sanitized;
	}

	/**
	 * Render the sample text field.
	 */
	public function render_sample_text_field() {
		$options = get_option( 'wp_plugin_template_options', array() );
		$value   = isset( $options['sample_text'] ) ? $options['sample_text'] : '';

		printf(
			'<input type="text" name="wp_plugin_template_options[sample_text]" value="%s" class="regular-text">',
			esc_attr( $value )
		);
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		Template::display( 'admin/settings-page' );
	}
}
