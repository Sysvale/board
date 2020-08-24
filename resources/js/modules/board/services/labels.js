import http from '../../../http';

export const getLabels = () => http.get('/labels');
