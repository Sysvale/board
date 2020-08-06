import axios from 'axios';

axios.defaults.headers.common = null;
export const getAddress = cep => axios.get(`https://viacep.com.br/ws/${cep}/json/`);
