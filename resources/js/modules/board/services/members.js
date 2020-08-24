import http from '../../../http';

export const getMembers = () => http.get('/members');
