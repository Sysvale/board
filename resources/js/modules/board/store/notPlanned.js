import { set } from 'vue';
import lodash from 'lodash';
import makeRequestStore from '../../../core/utils/makeRequestStore';
import generateUUID from '../../../core/utils/generateUUID';

import {
	getNotPlannedBacklog,
} from '../services';

const modules = [
	{ getNotPlannedBacklog },
];

const initialState = () => ({
	items: [],
});

export default {
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module),
		}), {}),
	},
	state: {
		...initialState(),
	},
	mutations: {
		setItems(state, payload) {
			state.items = payload;
		},
		setTaskStatus(state, payload) {
			const { taskId, newStatus } = payload;
			let xItems = [...state.items];
			xItems.forEach(({ id }, i) => {
				if(id === taskId) {
					xItems[i].status = newStatus;
				}
			});
			state.items = [...xItems];
		},
		addNewTask(state, payload) {
			const { status, title } = payload;
			let xItems = [...state.items];
			xItems = [
				{
					id: generateUUID(),
					title,
					status,
				},
				...xItems,
			];
			state.items = [...xItems];
		}
	},
	getters: {
		getItems({ items }) {
			return items;
		},
		getTaskById({ items }) {
			return id => items.filter(item => item.id === id)[0];
		},
		getTasksByStatus({ items }) {
			return (taskStatus) => items
				.filter(({ status }) => status === taskStatus) || [];
		},
	},
}
