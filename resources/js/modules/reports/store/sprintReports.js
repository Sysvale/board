import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getSprintReportByTeamId,
} from '../services/sprintReports';

const modules = [
	{ getSprintReportByTeamId },
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
		selectedTeamId: null,
	},
	getters: {
		itemsByTeam(state) {
			return (teamId) => state.items.filter(({ teamIds }) => {
				return teamIds.indexOf(teamId) > -1;
			});
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},

		setSelectedTeamId(state, payload) {
			state.selectedTeamId = payload;
		},
	},
}
