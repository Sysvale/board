import http from '../../../http';

export const getPlanningLists = ({ workspace_id }) => http.get(`/lists/planning/${workspace_id}`);

export const getProblemsLists = () => http.get('/lists/problems');

export const getCompanyPlanningLists = () => http.get('/lists/planning/company');
