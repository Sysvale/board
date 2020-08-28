import axios from 'axios';

const BASE_URL = 'https://gitlab.com/api/v4/projects/586906';
const TOKEN = 'QNj1ZykwP11RzLgiqXMv';

axios.interceptors.request.use(function (config) {
    config.headers.Authorization = `Bearer ${TOKEN}`;

    return config;
});

export const getIssues = (params) => axios.get(
    `${BASE_URL}/issues`,
    { params: { scope: 'all', state: 'opened', ...params } }
);

export const getIssuesAmount = () => axios.get(
    `${BASE_URL}/issues_statistics`,
    { params: { scope: 'all' } }
);
