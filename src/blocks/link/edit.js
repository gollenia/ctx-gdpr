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
import { Icon } from '@wordpress/components';
import { createPortal, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function edit({ ...props }) {
	const {
		attributes: {
			title,
			modalFull,
			icon,
			modalTitle,
			saveSettingsButtonTitle,
			neededCookiesLabelText,
			thirdPartyCookiesLabelText,
			thirdPartyCookiesDefault,
		},
		setAttributes,
	} = props;

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
			<div className="ctx-gdpr-button">
				<i className="material-icons material-symbols-outlined">
					{icon}
				</i>
				<RichText
					tagName="span"
					value={title}
					onChange={(title) => setAttributes({ title })}
					placeholder={__('Cookie Settings', 'ctx-gdpr')}
				/>
			</div>
			<Inspector
				{...props}
				showModal={showModal}
				setShowModal={setShowModal}
			/>
			{createPortal(
				<div
					role="dialog"
					title={modalTitle || __('Cookie Settings', 'ctx-gdpr')}
					className={`ctx-gdpr-modal ${showModal ? 'is-visible' : ''}`}
					onRequestClose={() => setShowModal(false)}
					isFullScreen={modalFull}
					width={600}
				>
					<div className="ctx-gdpr-modal-content">
						<div className="ctx-gdpr-modal-header">
							<RichText
								tagName="h2"
								value={modalTitle}
								placeholder={__('Cookie Settings', 'ctx-gdpr')}
								onChange={(value) =>
									setAttributes({ modalTitle: value })
								}
							/>

							<div
								className="ctx-gdpr-modal-close"
								onClick={() => setShowModal(false)}
							>
								<Icon icon="no-alt" />
							</div>
						</div>

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
								placeholder={__(
									'Accept required cookies',
									'ctx-gdpr'
								)}
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
								placeholder={__(
									'Accept third party cookies',
									'ctx-gdpr'
								)}
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
									setAttributes({
										neededCookiesLabelText: value,
									})
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
					</div>
				</div>,
				document.getElementsByClassName('edit-site-visual-editor')[0]
			)}
		</div>
	);
}
