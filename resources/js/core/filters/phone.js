export default function (value) {
	if (!value) {
		return '';
	}

	const clearedValue = value.replace(/[^0-9]+/g, '');

	if (clearedValue.length === 10) {
		return `(${clearedValue.substring(0, 2)}) ${clearedValue.substring(2, 6)}-${clearedValue.substring(6, 10)}`;
	}

	if (clearedValue.length === 11) {
		return `(${clearedValue.substring(0, 2)}) ${clearedValue.substring(2, 7)}-${clearedValue.substring(7, 11)}`;
	}

	return value;
}
