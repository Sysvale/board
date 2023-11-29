import http from '../../../http';

export const getProcesses = () => http.get('/processes');

export const getProcess = (id) => http.get(`/processes/${id}`);

export const createProcess = (payload) => http.post('/processes', payload);

export const updateProcess = ({ id, ...params }) => http.put(`/processes/${id}`, params);

export const deleteProcess = (id) => http.delete(`/processes/${id}`);
