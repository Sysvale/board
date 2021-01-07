import http from '../../../http';

export const createEvent = (payload) => http.post('/events', payload);

export const updateEvent = ({ id, ...params }) => http.put(`/events/${id}`, params);

export const deleteEvent = (id) => http.delete(`/events/${id}`);

export const getEventsByTeam = (teamId) => http.get(`/events/${teamId}`);
