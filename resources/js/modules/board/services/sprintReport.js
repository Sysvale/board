import http from '../../../http';

export const createSprintReport = (payload) => http.post('/sprint-reports', payload);

export const getSprintReports = () => http.get('/sprint-reports');
