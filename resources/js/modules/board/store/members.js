import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getMembers
} from '../services/members';

const modules = [
	{ getMembers },
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
}
