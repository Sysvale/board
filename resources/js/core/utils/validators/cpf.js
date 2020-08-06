import CpfValidate from './rules/cpf';

const validator = {
	message: 'CPF inválido',

	validate(value, args) {
		return CpfValidate(value);
	},
};

export default validator;
