/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Inspector controls
 */
const Inspector = (props) => {
	const {
		attributes: { modalTitle, modalFull },
		setAttributes,
	} = props;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Icon', 'ctx-blocks')} initialOpen={true}>
					<CheckboxControl
						label={__('Full screen size', 'ctx-blocks')}
						checked={modalFull}
						onChange={(value) =>
							setAttributes({ modalFull: !modalFull })
						}
					/>

					<TextControl
						label={__('Modal Title', 'ctx-blocks')}
						value={modalTitle}
						onChange={(value) => {
							setAttributes({ modalTitle: value });
						}}
					/>
				</PanelBody>
			</InspectorControls>
		</>
	);
};

export default Inspector;
