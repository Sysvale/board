import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getMembers,
	createMember,
	updateMember,
	deleteMember,
} from '../services/members';

const modules = [
	{ getMembers },
	{ createMember },
	{ updateMember },
	{ deleteMember },
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
			return state.items.filter(({ workspacesIds }) => {
				// return workspacesIds.indexOf(rootGetters['workspaces/currentWorkspace'].id) > -1;
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
