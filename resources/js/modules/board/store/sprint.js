import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getPlanningLists
} from '../services/sprint';

const modules = [
	{ getPlanningLists },
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
