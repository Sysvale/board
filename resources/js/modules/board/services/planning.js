import http from '../../../http';

export const getPlanningLists = () => http.get('/planning-lists');
