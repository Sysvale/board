import makeRequestStore from './makeRequestStore';
import generateUUID from './generateUUID';

export default (modules = []) => {
	return {
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
			},
			removeTask(state, payload) {
				state.items = [
					...state.items.filter(({ id }) => id !== payload.id)
				];
			},
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
		}
	}
};
