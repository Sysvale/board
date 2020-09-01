import http from '../../../http';

export const getDefaultLists = (params) => http.get('/lists/default', { params });

export const getDevlogLists = (params) => http.get('/lists/devlog', { params });

export const getPlanningLists = () => http.get('/lists/planning');

export const getCurrentSprintSummaryByTeam = (teamId) => http.get(`/sprint/summary/current/${teamId}`);
