import http from '../../../http';

export const getTeams = () => http.get('/teams');
