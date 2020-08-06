export default function (value) {
	return String(value).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
}
