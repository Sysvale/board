import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getIssuesLists,
} from '../services/issues';

const modules = [
	{ getIssuesLists },
];

export default {
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module),
		}), {}),
	},
	state: {
		items: [],
	},
	mutations: {
		setItems (state, payload) {
			state.items = payload;
		},
	},
}
