<?php
/**
 * Template rendering class.
 *
 * @package WPPluginTemplate
 */

namespace WPPluginTemplate\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Handles loading and rendering of PHP view templates.
 */
class Template {

	/**
	 * Render a template file and return the output as a string.
	 *
	 * Loads a PHP file from the views/ directory and passes variables to it.
	 *
	 * Usage:
	 *   $html = Template::render( 'admin/settings-page', array( 'title' => 'Hello' ) );
	 *   echo $html;
	 *
	 *   Template::display( 'sample-template', array( 'title' => 'Hello' ) );
	 *
	 * @param string $template Template name relative to views/ (without .php extension).
	 * @param array  $vars     Associative array of variables to pass to the template.
	 * @return string Rendered HTML output. Empty string if template not found.
	 */
	public static function render( $template, $vars = array() ) {
		$file = WP_PLUGIN_TEMPLATE_DIR . 'views/' . $template . '.php';

		if ( ! file_exists( $file ) ) {
			return '';
		}

		ob_start();

		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- Intentional for template variable passing.
		extract( $vars );

		include $file;

		return ob_get_clean();
	}

	/**
	 * Render a template file and output it directly.
	 *
	 * @param string $template Template name relative to views/ (without .php extension).
	 * @param array  $vars     Associative array of variables to pass to the template.
	 */
	public static function display( $template, $vars = array() ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped within template files.
		echo self::render( $template, $vars );
	}
}
