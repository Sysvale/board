import { set } from 'vue';
import lodash from 'lodash';
import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getUserStories,
} from '../services';

const modules = [
	{ getUserStories },
];

const initialState = () => ({
	items: [],
});

function generateUUID() { // Public Domain/MIT
	var d = new Date().getTime();//Timestamp
	var d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
			var r = Math.random() * 16;//random number between 0 and 16
			if(d > 0){//Use timestamp until depleted
					r = (d + r)%16 | 0;
					d = Math.floor(d/16);
			} else {//Use microseconds since page-load if supported
					r = (d2 + r)%16 | 0;
					d2 = Math.floor(d2/16);
			}
			return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
	});
}

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
			const { status, title, storyId } = payload;
			if(storyId) {
				let xItems = [...state.items];
				xItems.forEach(({ id, tasks }, i) => {
					if(storyId === id) {
						xItems[i].tasks = [
							{
								id: generateUUID(),
								title,
								status,
								cardId: storyId,
							},
							...tasks,
						];
					}
				});
				console.log(xItems);
				state.items = [...xItems];
			}
		}
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
