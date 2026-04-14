/**
 * Sample Block – Save component.
 *
 * @package WPPluginTemplate
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function returns the markup stored in the database.
 *
 * @return {Element} Block frontend element.
 */
export default function save() {
	const blockProps = useBlockProps.save();

	return (
		<div { ...blockProps }>
			<p>{ __( 'Sample Block – Frontend', 'wp-plugin-template' ) }</p>
		</div>
	);
}
