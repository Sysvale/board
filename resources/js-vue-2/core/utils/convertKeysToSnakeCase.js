const convertKeysToSnakeCase = (data) => {
	if (_.isArray(data)) {
		return data.map((element) => {
			if (_.isObject(element) || _.isArray(element)) {
				return convertKeysToSnakeCase(element);
			}
			return element;
		});
	}
	const newData = {};
	Object.keys(data).forEach((key) => {
		if (_.isObject(data[key]) || _.isArray(data[key])) {
			newData[_.snakeCase(key)] = convertKeysToSnakeCase(data[key]);
		} else {
			newData[_.snakeCase(key)] = data[key];
		}
	});

	return newData;
};

export default convertKeysToSnakeCase;
