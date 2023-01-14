import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getBacklogLabels,
	createBacklogLabel,
	updateBacklogLabel,
	deleteBacklogLabel,
} from '../services/backlogLabels';

const modules = [
	{ getBacklogLabels },
	{ createBacklogLabel },
	{ updateBacklogLabel },
	{ deleteBacklogLabel },
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
			state.items = convertKeysToCamelCase(payload);
		},
	},
};
