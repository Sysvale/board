import makeRequestStore from '../../../core/utils/makeRequestStore';
import generateUUID from '../../../core/utils/generateUUID';

import {
	getUserStories,
} from '../services';

const modules = [
	{ getUserStories },
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
		setTasksByUserStoryId({ items }, payload) {
			return id => (items) => {
				items.filter(item => item.id === id)[0].tasks = payload;
			}
		},
		setTaskStatus(state, payload) {
			const { storyId, taskId, newStatus } = payload;
			let xItems = [...state.items];
			xItems.forEach(({ id, tasks }, i) => {
				if(storyId === id) {
					tasks.forEach(({ id }, j) => {
						if(id === taskId) {
							xItems[i].tasks[j].status = newStatus;
						}
					});
				}
			});
			state.items = [...xItems];
		},
		addNewTask(state, payload) {
			const { listId, title, storyId } = payload;
			let xItems = [...state.items];
			xItems.forEach(({ id }, i) => {
				if(storyId === id) {
					xItems[i].tasks = [
						{
							id: generateUUID(),
							title,
							status: listId,
							cardId: storyId,
						},
						...xItems[i].tasks,
					];
				}
			});
			state.items = [...xItems];
		},
		removeTask(state, payload) {
			const { listId, id } = payload;
			console.log(listId, id);
			let xItems = [...state.items];
			xItems.forEach((_, i) => {
				// tá percorrendo tudo denecessariamente, pensar numa maneira mais legal
				xItems[i].tasks = xItems[i]
					.tasks.filter((task) => task.id !== id);
			});
			state.items = [...xItems];
		},
	},
	getters: {
		getItems({ items }) {
			return items;
		},
		getUserStoryById({ items }) {
			return id => items.filter(item => item.id === id)[0];
		},
		getTasksByUserStoryIdAndStatus(_, getters) {
			return (id, listKey) => [...getters.getUserStoryById(id)
				.tasks.filter(({ status }) => status === listKey)] || [];
		},
	},
}
