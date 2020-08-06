export default function (value) {
	if (!value || value.length === 0) {
		return '';
	}

	const cpf = value.replace(/[^0-9]+/g, '');

	if (cpf.length === 11) {
		return `${cpf.substring(0, 3)}.${cpf.substring(3, 6)}.${cpf.substring(6, 9)}-${cpf.substring(9, 11)}`;
	}

	return cpf;
}
