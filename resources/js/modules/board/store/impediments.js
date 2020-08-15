import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getImpediments,
	createImpediment,
} from '../services';

const modules = [
	{ getImpediments },
	{ createImpediment },
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
	mutations: {
		setItems(state, payload) {
			state.items = payload;
		},
		addItem(state, payload) {
			state.items = [
				payload,
				...state.items,
			];
		},
	},
}
