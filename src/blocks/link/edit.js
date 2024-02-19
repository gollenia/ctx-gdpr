/**
 * Internal dependencies
 */
import Inspector from './inspector';
/**
 * Wordpress dependencies
 */
import {
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { Modal } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function edit({ ...props }) {
	const { clientId } = props;

	const {
		title,
		modalFull,
		modalTitle,
		acceptAllButtonTitle,
		saveSettingsButtonTitle,
		neededCookiesLabelText,
		thirdPartyCookiesLabelText,
		thirdPartyCookiesDefault,
	} = props.attributes;

	const setAttributes = props.setAttributes;

	const template = [
		[
			'core/paragraph',
			{
				content: __(
					'This website collects cookies to deliver better user experience. By clicking "accept all" you you consent to our use of cookies. You can also choose what cookies you want to accept or reject. You can change your cookie settings at any time. Learn more in our <a href="#">privacy policy.</a>',
					'ctx-gdpr'
				),
			},
		],
	];

	const [showModal, setShowModal] = useState(false);

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'ctx:contitional__inner' },
		{ template }
	);

	return (
		<div {...blockProps}>
			<RichText
				tagName="span"
				value={title}
				onChange={(value) => props.setAttributes({ title: value })}
				placeholder={__('Cookie Settings', 'ctx-gdpr')}
				onDoubleClick={() => {
					setShowModal(true);
				}}
			/>
			<Inspector {...props} />
			{showModal && (
				<Modal
					title={modalTitle}
					className="ctx-gdpr-modal"
					onRequestClose={() => setShowModal(false)}
					isFullScreen={modalFull}
					width={600}
				>
					<div {...innerBlocksProps}></div>
					<div className="ctx-gdpr-modal-checkbox">
						<input
							type="checkbox"
							id="neededCookies"
							name="neededCookies"
							value="neededCookies"
							disabled={true}
							checked={true}
						/>
						<RichText
							value={neededCookiesLabelText}
							tagName="div"
							placeholder={__('Required Cookies', 'ctx-gdpr')}
							onChange={(value) =>
								setAttributes({
									neededCookiesLabelText: value,
								})
							}
						/>
					</div>
					<div className="ctx-gdpr-modal-checkbox">
						<input
							type="checkbox"
							id="acceptOther"
							name="acceptOther"
							value="acceptOther"
							checked={thirdPartyCookiesDefault}
							onChange={(value) => {
								setAttributes({
									thirdPartyCookiesDefault:
										!thirdPartyCookiesDefault,
								});
							}}
						/>
						<RichText
							value={thirdPartyCookiesLabelText}
							tagName="div"
							placeholder={__('Third Party Cookies', 'ctx-gdpr')}
							onChange={(value) =>
								setAttributes({
									thirdPartyCookiesLabelText: value,
								})
							}
						/>
					</div>

					<div className="ctx-gdpr-modal-footer">
						<RichText
							className="ctx-gdpr-modal-button"
							value={neededCookiesLabelText}
							placeholder={__('Accept All', 'ctx-gdpr')}
							onChange={(value) =>
								setAttributes({ neededCookiesLabelText: value })
							}
						/>
						<RichText
							className="ctx-gdpr-modal-button"
							value={saveSettingsButtonTitle}
							placeholder={__('Save Settings', 'ctx-gdpr')}
							onChange={(value) =>
								setAttributes({
									saveSettingsButtonTitle: value,
								})
							}
						/>
					</div>
				</Modal>
			)}
		</div>
	);
}
