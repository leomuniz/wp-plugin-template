/**
 * Sample Block – Editor component.
 *
 * @package WPPluginTemplate
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The edit function renders the block in the editor.
 *
 * @return {Element} Block editor element.
 */
export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<p>{ __( 'Sample Block – Editor', 'wp-plugin-template' ) }</p>
		</div>
	);
}
