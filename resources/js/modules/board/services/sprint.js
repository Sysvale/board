import http from '../../../http';

export const getDefaultLists = () => http.get('/lists/default');

export const getDevlogLists = () => http.get('/lists/devlog');

export const getPlanningLists = () => http.get('/lists/planning');
