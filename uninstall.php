<?php
/**
 * Uninstall handler.
 *
 * Fired when the plugin is deleted via the WordPress admin.
 *
 * @package WPPluginTemplate
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Clean up plugin options.
delete_option( 'wp_plugin_template_options' );
