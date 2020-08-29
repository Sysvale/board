import axios from 'axios';

const BASE_URL = process.env.MIX_GITLAB_BASE_URL;
const TOKEN = process.env.MIX_GITLAB_TOKEN;

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
