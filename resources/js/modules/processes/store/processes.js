import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getProcess,
	getProcesses,
	createProcess,
	updateProcess,
	deleteProcess,
} from '../services/processes';

const modules = [
	{ getProcess },
	{ getProcesses },
	{ createProcess },
	{ updateProcess },
	{ deleteProcess },
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
				return workspaceId === rootGetters['workspaces/currentWorkspace'].id;
			})
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
	},
}
