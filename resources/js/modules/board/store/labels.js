import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import {
	getLabels
} from '../services/labels';

const modules = [
	{ getLabels },
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
		lowerCaseLabels: [],
	},
	mutations: {
		setItems(state, payload) {
			state.items = convertKeysToCamelCase(payload);
			state.lowerCaseLabels = payload;
		},
	},
	getters: {
		lowercaseLabels(state) {
			return state.lowerCaseLabels;
		},
	}
}
