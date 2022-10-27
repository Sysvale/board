import http from '../../../http';

export const getLabels = () => http.get('/labels');

export const createLabel = (payload) => http.post('/labels', payload);

export const updateLabel = ({ id, ...params }) => http.put(`/labels/${id}`, params);

export const deleteLabel = (id) => http.delete(`/labels/${id}`);

export const getLabelsByWorkspaceId = (workspaceId) => http.get(`/labels/workspace/${workspaceId}`);
