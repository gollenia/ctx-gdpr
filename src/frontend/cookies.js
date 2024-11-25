import apiFetch from '@wordpress/api-fetch';

const cookies = () => {
	const sendConsent = (event) => {
		const all = event.target.dataset.all === '1';
		apiFetch({
			path: '/ctx-gdpr/v2/consent',
			method: 'POST',
			data: { all },
		})
			.then((response) => {
				if (response.success) {
					document
						.getElementById('ctx-gdpr-modal')
						.classList.remove('is-visible');
					if (!all) return;
					window.location.reload();
				}
			})
			.catch((error) => {
				console.error('error', error);
			});
	};

	window.addEventListener('DOMContentLoaded', () => {
		document
			.querySelectorAll('.ctx-gdpr-consent-button')
			.forEach((button) => {
				button.addEventListener('click', sendConsent);
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
