import makeRequestStore from './makeRequestStore';
import generateUUID from './generateUUID';
import upperCamelCase from './upperCamelCase';

const initialState = (lists) => {
	let state = {};
	lists.forEach(({ id }) => {
		state = {
			...state,
			[id]: [],
		}
	});
	return state;
}

const generateMutations = (lists) => {
	let mutations = {};
	lists.forEach(({ id }) => {
		let mutationName = `set${upperCamelCase(id)}`;
		mutations = {
			[mutationName]: (state, payload) => {
				state[key] = payload;
			},
			...mutations,
		};
	});
	return mutations;
}

const generateGetters = (lists) => {
	let getters = {};
	lists.forEach(({ id }) => {
		let getterName = `get${upperCamelCase(id)}`;
		getters = {
			[getterName]: (state) => {
				return state[id];
			},
			...getters,
		};
	});
	return getters;
}

export default (lists, modules = []) => {
	return {
		namespaced: true,
		modules: {
			...modules.reduce((acc, module) => ({
				...acc,
				...makeRequestStore(module),
			}), {}),
		},
		state: {
			...initialState(lists),
		},
		mutations: {
			setCards(state, payload) {
				Object.keys(payload).forEach((key) => {
					state[key] = payload[key];
				})
			},
			...generateMutations(lists),
			addNewTask(state, payload) {
				const { listId, ...args } = payload;
				state[listId] = [
					{
						...args,
					},
					...state[listId],
				];
			},
			removeTask(state, payload) {
				const { listId, id } = payload;
				state[listId] = [
					...state[listId].filter((card) => card.id !== id),
				];
			},
		},
		getters: {
			...generateGetters(lists),
		},
	}
};
