import http from '../../../http';

export const getMilestones = () => http.get('/milestones');

export const getMilestone = (id) => http.get(`/milestones/${id}`);

export const createMilestone = (payload) => http.post('/milestones', payload);

export const updateMilestone = ({ id, ...params }) => http.put(`/milestones/${id}`, params);

export const deleteMilestone = (id) => http.delete(`/milestones/${id}`);

export const getNotStartedItems = (id) => http.get(`/milestones/${id}/backlog-items/not-started`);

export const getOnGoingItems = (id) => http.get(`/milestones/${id}/backlog-items/on-going`);

export const getFinishedItems = (id) => http.get(`/milestones/${id}/backlog-items/finished`);
