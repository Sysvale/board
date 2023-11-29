import http from '../../../http';

export const getBoards = () => http.get('/boards');
