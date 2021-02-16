import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getBoards
} from '../services/boards';

const modules = [
	{ getBoards },
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
	getters: {
		itemsByWorkspace(state, _, __, rootGetters) {
			return state.items.filter(({ workspaceId }) => {
				// return workspaceId === rootGetters['workspaces/currentWorkspace'].id
				return true;
			})
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
	},
}
