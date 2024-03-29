import http from '../../../http';

export const getMembers = () => http.get('/members');

export const createMember = (payload) => http.post('/members', payload);

export const updateMember = ({ id, ...params }) => http.put(`/members/${id}`, params);

export const deleteMember = (id) => http.delete(`/members/${id}`);

export const resendWelcomeMail = (userId) => http.post('/users/resend-welcome-mail', { user_id: userId });
