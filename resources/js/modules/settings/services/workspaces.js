import http from '../../../http';

export const getWorkspaces = () => http.get('/workspaces');

export const createWorkspace = (payload) => http.post('/workspaces', payload);

export const updateWorkspace = ({ id, ...params }) => http.put(`/workspaces/${id}`, params);

export const deleteWorkspace = (id) => http.delete(`/workspaces/${id}`);
