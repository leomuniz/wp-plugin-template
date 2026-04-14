<?php
/**
 * Admin settings page template.
 *
 * @package WPPluginTemplate
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'wp_plugin_template_settings' );
		do_settings_sections( 'wp-plugin-template' );
		submit_button();
		?>
	</form>
</div>
