import AxiosCache from 'axios-cache-plugin';

const axios = require('axios');

let token = document.head.querySelector('meta[name="csrf-token"]');

if (!token) {
	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

let typeUser = document.head.querySelector('meta[name="type-user"]');

let axiosInstance = axios.create({
	withCredentials: false,
	headers: {
		'X-Requested-With': 'XMLHttpRequest',
		'X-CSRF-TOKEN': (token.content || null),
	},
});

const wrapper = AxiosCache(axiosInstance, {
	maxCacheSize: 15 * 60 * 1000,
});
export default wrapper;
