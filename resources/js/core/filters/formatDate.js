export default function (value, entryFormat = 'YYYY-MM-DD', outputFormat = 'DD/MM/YYYY') {
	if (!value) {
		return '';
	}

	if (!moment(value, entryFormat).isValid()) {
		return value;
	}

	if (outputFormat == 'fromNow') {
		return moment(value, entryFormat).fromNow();
	}

	return moment(value, entryFormat).format(outputFormat);
}
