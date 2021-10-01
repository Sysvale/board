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
		itemsByTeam(state) {
			return (teamId) => state.items.filter(({ teamIds }) => {
				return teamIds.indexOf(teamId) > -1;
			})
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
	},
}
