import http from '../../../http';

export const getUserStoriesByTeam = (teamId) => http.get(`/user-stories/${teamId}`);
