import upperCamelCase from './upperCamelCase';

export default (module, fields) => {
	let computed = {};
	fields.forEach((field) => {
		computed = {
			...computed,
			[field]: {
				get() {
					return this.$store.getters[`${module}/get${upperCamelCase(field)}`];
				},

				set(newValue) {
					this.$store.commit(`${module}/set${upperCamelCase(field)}`, newValue, { root: true });
				},
			},
		};
	});
	return computed;
};
