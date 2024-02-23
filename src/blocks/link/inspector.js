/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import {
	Button,
	CheckboxControl,
	ComboboxControl,
	PanelBody,
	TextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { getBlockTypes } from '@wordpress/blocks';

import { useEntityProp } from '@wordpress/core-data';

/**
 * Inspector controls
 */
const Inspector = (props) => {
	const {
		attributes: { modalTitle, modalFull, icon },
		setAttributes,
		showModal,
		setShowModal,
	} = props;

	const [forbiddenBlocks, setForbiddenBlocks] = useEntityProp(
		'root',
		'site',
		'privacy_forbidden_blocks'
	);

	const [blockEmbedded, setBlockEmbedded] = useEntityProp(
		'root',
		'site',
		'privacy_block_embedded'
	);

	const options = getBlockTypes().map((blockType) => {
		if (forbiddenBlocks.includes(blockType.title)) return;

		return {
			label: blockType.title,
			value: blockType.name,
		};
	});

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__('Appearance', 'ctx-gdpr')}
					initialOpen={true}
				>
					<CheckboxControl
						label={__('Full screen size', 'ctx-gdpr')}
						checked={modalFull}
						onChange={(value) =>
							setAttributes({ modalFull: !modalFull })
						}
					/>

					<TextControl
						label={__('Icon', 'ctx-gdpr')}
						value={icon}
						onChange={(icon) => setAttributes({ icon })}
					/>

					<Button
						label={__('Edit Popup', 'ctx-gdpr')}
						value={modalTitle}
						onClick={(value) => setShowModal(true)}
						variant="secondary"
					>
						{__('Edit Popup', 'ctx-gdpr')}
					</Button>
				</PanelBody>

				<PanelBody
					title={__('Forbidden Blocks', 'ctx-gdpr')}
					initialOpen={true}
				>
					<p>
						{__(
							'You can define here, which blocks are prohibited when the user did not accept 3rd party cookies.',
							'ctx-gdpr'
						)}
					</p>
					<CheckboxControl
						label={__('Embedded Blocks', 'ctx-gdpr')}
						checked={blockEmbedded}
						onChange={(value) => setBlockEmbedded(!blockEmbedded)}
					/>

					<ComboboxControl
						label={__('Other Blocks', 'ctx-gdpr')}
						value={forbiddenBlocks}
						onChange={(value) => {
							const blocks = forbiddenBlocks.length
								? forbiddenBlocks.split(',')
								: [];

							blocks.push(value);

							setForbiddenBlocks(blocks.join(','));
						}}
						options={options}
					/>

					<div className="ctx-gdpr-tags">
						{!forbiddenBlocks ? (
							<span className="ctx-gdpr-tag-empty"></span>
						) : (
							<>
								{forbiddenBlocks.split(',').map((block) => {
									return (
										<span className="ctx-gdpr-tag">
											<span>{block}</span>
											<button
												onClick={() => {
													const blocks =
														forbiddenBlocks.split(
															','
														);
													const index =
														blocks.indexOf(block);
													blocks.splice(index, 1);
													setForbiddenBlocks(
														blocks.join(',')
													);
												}}
											>
												<svg
													xmlns="http://www.w3.org/2000/svg"
													viewBox="0 0 24 24"
													width="24"
													height="24"
													aria-hidden="true"
													focusable="false"
												>
													<path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path>
												</svg>
											</button>
										</span>
									);
								})}
							</>
						)}
					</div>
				</PanelBody>
			</InspectorControls>
		</>
	);
};

export default Inspector;
