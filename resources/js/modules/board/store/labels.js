import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getLabels
} from '../services';

const modules = [
	{ getLabels },
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
