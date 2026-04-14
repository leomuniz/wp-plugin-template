<?php
/**
 * Sample view template.
 *
 * Demonstrates how to use Template::render() / Template::display() with variables.
 *
 * Usage:
 *   use WPPluginTemplate\Core\Template;
 *
 *   echo Template::render( 'sample-template', array(
 *       'title'   => 'Hello World',
 *       'content' => '<p>This is the content.</p>',
 *   ) );
 *
 * @package WPPluginTemplate
 *
 * @var string $title   The title to display.
 * @var string $content The content to display.
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wp-plugin-template-sample">
	<?php if ( ! empty( $title ) ) : ?>
		<h2><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>

	<?php if ( ! empty( $content ) ) : ?>
		<div class="wp-plugin-template-sample__content">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	<?php endif; ?>
</div>
