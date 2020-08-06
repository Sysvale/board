import http from '../../http';

export const getDictionaryGroupService = (params) => http.get('dictionary/simple-list', { params });