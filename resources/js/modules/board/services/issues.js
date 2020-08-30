import http from '../../../http';

export const getIssuesLists = () => http.get('/lists/issues');
