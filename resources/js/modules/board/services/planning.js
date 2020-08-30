import http from '../../../http';

export const getPlanningLists = () => http.get('/lists/planning');

export const getProblemsLists = () => http.get('/lists/problems');
