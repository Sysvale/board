import { defineRule } from 'vee-validate';
import is_valid from '../utils/cnpj';

defineRule('cnpj', (value) => {
  if (!is_valid(value)) {
	return 'CNPJ inválido';
  }
  return true;
});
