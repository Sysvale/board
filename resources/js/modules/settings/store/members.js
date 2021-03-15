import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getMembers,
	createMember,
	updateMember,
	deleteMember,
	resendWelcomeMail,
} from '../services/members';

const modules = [
	{ getMembers },
	{ createMember },
	{ updateMember },
	{ deleteMember },
	{ resendWelcomeMail },
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
			})
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
	},
}
