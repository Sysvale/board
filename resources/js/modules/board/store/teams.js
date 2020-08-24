import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getTeams
} from '../services/teams';

const modules = [
	{ getTeams },
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
		setItems(state, payload) {
			state.items = payload;
		},
	},
}
