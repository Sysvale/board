import http from '../../../http';

export const getTeams = () => http.get('/teams');

export const createTeam = (payload) => http.post('/teams', payload);

export const updateTeam = ({ id, ...params }) => http.put(`/teams/${id}`, params);

export const deleteTeam = (id) => http.delete(`/teams/${id}`);
