import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getWorkspaces
} from '../services/workspaces';

const modules = [
	{ getWorkspaces },
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
		selectedWorkpace: null,
	},
	getters: {
		currentWorkspace(state) {
			return state.selectedWorkpace;
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
		setSelectedWorkspace(state, workspace) {
			state.selectedWorkpace = workspace;
		},
	},
}
