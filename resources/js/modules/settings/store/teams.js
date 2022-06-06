import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getTeams,
	createTeam,
	updateTeam,
	deleteTeam,
} from '../services/teams';

const modules = [
	{ getTeams },
	{ createTeam },
	{ updateTeam },
	{ deleteTeam },
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
			return state.items.filter(({ workspaceIds }) => {
				return workspaceIds.indexOf(rootGetters['workspaces/currentWorkspace'].id) > -1;
			});
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
	},
}
