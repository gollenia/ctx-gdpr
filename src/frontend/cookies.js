const cookies = () => {
	const sendConsentRequest = async (all = false) => {
		const response = await fetch('/wp-json/ctx-gdpr/v1/consent', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ all: all ? 1 : 0 }),
		});

		const result = await response.json();

		return result;
	};

	window.addEventListener('DOMContentLoaded', () => {
		const consentCheckBox = document.getElementById(
			'ctx-gdpr-accept-third-party'
		);

		const okClick = document.getElementById('ctx-gdpr-modal-save');
		if (!okClick) return;
		okClick.addEventListener('click', () => {
			sendConsentRequest(consentCheckBox.checked).then((result) => {
				if (result.success) {
					document
						.getElementById('ctx-gdpr-modal')
						.classList.remove('is-visible');
				}
			});
		});

		const allClick = document.getElementById('ctx-gdpr-modal-accept-all');
		if (!allClick) return;
		allClick.addEventListener('click', () => {
			consentCheckBox.checked = true;
			sendConsentRequest(consentCheckBox.checked).then((result) => {
				if (result.success) {
					document
						.getElementById('ctx-gdpr-modal')
						.classList.remove('is-visible');
					window.location.reload();
				}
			});
		});

		const openDialog = document.getElementById('ctx-gdpr-link');

		if (!openDialog) return;
		openDialog.addEventListener('click', () => {
			document
				.getElementById('ctx-gdpr-modal')
				.classList.add('is-visible');
		});
	});
};

export default cookies;
