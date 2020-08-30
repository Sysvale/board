import http from '../../../http';

export const getDefaultLists = () => http.get('/lists/default');

export const getDevlogLists = (params) => http.get('/lists/devlog', { params });

export const getPlanningLists = () => http.get('/lists/planning');
