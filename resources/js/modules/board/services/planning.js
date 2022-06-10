import http from '../../../http';

export const getPlanningLists = (workspaceId) => http.get(`/lists/planning/${workspaceId}`);

export const getProblemsLists = () => http.get('/lists/problems');

export const getCompanyPlanningLists = () => http.get('/lists/planning/company');
