import { localize } from '@vee-validate/i18n';
import {
	required, email, min, min_value, integer, regex,
} from '@vee-validate/rules';
import isValidCNPJ from './rules/cnpj';
import pt_BR_validation from './pt_BR_validation';

export default (VeeValidate) => {
	VeeValidate.configure({
		// Generates an English message locale generator
		generateMessage: localize('pt-BR', {
			messages: pt_BR_validation,
		}),
	});

	VeeValidate.defineRule('required', required);
	VeeValidate.defineRule('not_empty', (value) => {
		if (!value || value?.length == 0) {
			return 'O campo não pode ser vazio';
		}
		return true;
	});
	VeeValidate.defineRule('cnpj', (value) => {
		if (!isValidCNPJ(value)) {
			return 'CNPJ inválido';
		}
		return true;
	});
	VeeValidate.defineRule('email', email);
	VeeValidate.defineRule('min', min);
	VeeValidate.defineRule('min_value', min_value);
	VeeValidate.defineRule('integer', integer);
	VeeValidate.defineRule('regex', regex);
};
