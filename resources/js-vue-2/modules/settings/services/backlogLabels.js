import http from '../../../http';

export const getBacklogLabels = () => http.get('/backlog-labels');

export const createBacklogLabel = (payload) => http.post('/backlog-labels', payload);

export const updateBacklogLabel = ({ id, ...params }) => http.put(`/backlog-labels/${id}`, params);

export const deleteBacklogLabel = (id) => http.delete(`/backlog-labels/${id}`);
