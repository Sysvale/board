const convertKeysToCamelCase = (data) => {
	if (_.isArray(data)) {
		return data.map((element) => {
			if (_.isObject(element) || _.isArray(element)) {
				return convertKeysToCamelCase(element);
			}
			return element;
		});
	}
	const newData = {};
	Object.keys(data).forEach((key) => {
		if (_.isObject(data[key]) || _.isArray(data[key])) {
			newData[_.camelCase(key)] = convertKeysToCamelCase(data[key]);
		} else {
			newData[_.camelCase(key)] = data[key];
		}
	});

	return newData;
};

export default convertKeysToCamelCase;
