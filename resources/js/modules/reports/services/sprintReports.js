import http from '../../../http';

// eslint-disable-next-line import/prefer-default-export
export const getSprintReportByTeamId = (teamId) => http.get(`/sprint-reports/${teamId}`);
