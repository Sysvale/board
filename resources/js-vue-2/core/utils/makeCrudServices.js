import convertKeysToSnakeCase from './convertKeysToSnakeCase';

export default function makeCrudServices(entity, apiPath) {
	return {
		apiPath: `/api/${apiPath}`,
		httpClient: axios,

		[`get${_.upperFirst(_.camelCase(entity))}`](payload = {}) {
			payload = convertKeysToSnakeCase(payload);

			return axios.get(`/api/${apiPath}`, { params: payload })
				.then(response => response.data);
		},

		[`read${_.upperFirst(_.camelCase(entity))}`](id) {
			return axios.get(`/api/${apiPath}/${id}`)
				.then(response => response.data);
		},

		[`post${_.upperFirst(_.camelCase(entity))}`](payload) {
			payload = convertKeysToSnakeCase(payload);

			return axios.post(`/api/${apiPath}`, payload)
				.then(response => response.data);
		},

		[`put${_.upperFirst(_.camelCase(entity))}`]({ id, ...payload }) {
			payload = convertKeysToSnakeCase(payload);

			return axios.put(`/api/${apiPath}/${id}`, payload)
				.then(response => response.data);
		},

		[`delete${_.upperFirst(_.camelCase(entity))}`](id) {
			return axios.delete(`/api/${apiPath}/${id}`)
				.then(response => response.data);
		},
	};
}
