import lodash from 'lodash';

const upperCamelCase = string => lodash.upperFirst(lodash.camelCase(string));

export default upperCamelCase;
