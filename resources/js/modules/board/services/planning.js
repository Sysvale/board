import http from '../../../http';

export const getPlanningLists = () => http.get('/lists/planning');
