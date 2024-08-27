import Swal from 'sweetalert2';

const confirmationDefaultConfig = (customConfig = {}) => ({
	icon: 'warning',
	confirmButtonText: 'Sim, excluir',
	showCancelButton: true,
	customClass: {
		confirmButton: 'btn btn--indigo btn--md',
		cancelButton: 'btn btn--secondary btn--md',
	},
	reverseButtons: true,
	...customConfig,
});

const toastDefaultConfig = (customConfig = {}) => ({
	toast: true,
	position: 'top-end',
	timer: 5000,
	timerProgressBar: true,
	showConfirmButton: false,
	...customConfig,
});

const erouDefaultConfig = (message, response, customConfig = {}) => ({
	title: 'Ops...',
	icon: 'error',
	showCloseButton: true,
	showConfirmButton: false,
	html: (() => {
		let detailsMessage = '';
		if (process.env.NODE_ENV === 'development' && response) {
			detailsMessage = `
				<small>${response?.status}: ${response?.statusText}</small><br><br>
			`;
		}
		const isShowInfoMessage = response?.status !== 422 && response?.status !== 400;
		const infoMessage = isShowInfoMessage ? 'Se o problema persistir, contate o suporte.' : '';
		return `
			${message}<br>
			${infoMessage}<br><br>
			${detailsMessage}`;
	})(),
	customClass: {
		htmlContainer: 'swal__container',
		title: 'swal__title',
		actions: 'swal__actions',
	},
	buttonsStyling: false,
	...customConfig,
})

const confirmation = (title, html, config) => 
	Swal.fire({
		title,
		html,
		...confirmationDefaultConfig(),
		...config,
	});

const toast = (icon = 'success', title = 'Sucesso!', text = 'Ação concluída', config) => Swal.fire({
		icon,
		title,
		text,
		...toastDefaultConfig(),
		...config,
	});

const erou = (message, response, config) => Swal.fire({
		...erouDefaultConfig(message, response),
		...config,
	});
	

export default {
	confirmation,
	toast,
	erou,
	defaultConfig: {
		confirmation: confirmationDefaultConfig,
		toast: toastDefaultConfig,
		erou: erouDefaultConfig,
	}
}