import { initialState } from './requestsStates';
import makeMutations from './makeMutations';

export default (module, namespaced = false) => {
	const name = Object.keys(module)[0];

	return {
		[name]: {
			namespaced,
			state: { ...initialState },

			actions: {
				[name]({ commit }, params) {
					commit(`${name}Request`);

					return module[name](params)
						.then(({ data }) => {
							commit(`${name}Success`);
							return data;
						})
						.catch(error => commit(`${name}Failure`, error));
				},
			},

			mutations: {
				...makeMutations(name),
			},
		}
	};
};
