import http from '../../../http';

export const createGoal = (payload) => http.post('/goals', payload);

export const updateGoal = ({ id, ...params }) => http.put(`/goals/${id}`, params);

export const deleteGoal = (id) => http.delete(`/goals/${id}`);

export const getGoals = () => http.get(`/goals/`);
