export default function (value) {
	if (!value || value.length === 0) {
		return '';
	}

	const cns = value.replace(/[^0-9]+/g, '');

	if (cns.length === 15) {
		return `${cns.substring(0, 3)} ${cns.substring(3, 7)} ${cns.substring(7, 11)} ${cns.substring(11, 15)}`;
	}

	return cns;
}
