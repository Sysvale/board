import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';
import { BACKLOG } from '../constants/BoardListKeys';

import {
	deleteGoal,
	createGoal,
	updateGoal,
	getGoals,
} from '../services/goals';

const modules = [
	{ deleteGoal },
	{ createGoal },
	{ updateGoal },
	{ getGoals },
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
		getGoalByKey(state, _, rootState, rootGetters) {
			return (key) => {
				if(key.split('-').includes(BACKLOG)) {
					return state.items.filter(({ workspaceId }) => {
						return workspaceId === rootGetters['workspaces/currentWorkspace']?.id || (key.split('-')[1] || null) === workspaceId;
					})[0];
				}

				const team = rootState['teams'].items.filter((team) => team.key === key)[0];
				return state.items.filter(({ teamId }) => {
					return teamId === team.id;
				})[0];
			}
		},

		getGoalByTeamId(state) {
			return (id) => {
				return state.items.filter(({ teamId }) => {
					return teamId === id;
				})[0];
			}
		},
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
		},
	},
}
