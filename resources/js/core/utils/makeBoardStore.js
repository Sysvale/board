import makeRequestStore from './makeRequestStore';
import upperCamelCase from './upperCamelCase';
import convertKeysToCamelCase from './convertKeysToCamelCase';

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
				state[key] = convertKeysToCamelCase(payload);
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
					state[key] = convertKeysToCamelCase(payload[key]);
				})
			},
			setCard(state, payload) {
				const convertedPayload = convertKeysToCamelCase(payload);
				const { id, boardListId } = convertedPayload;
				let xItems = _.clone(state[boardListId]);
				xItems.forEach((item, i) => {
					if(item.id === id) {
						xItems[i] = {...convertedPayload};
					}
				});
				state[boardListId] = xItems;
			},
			...generateMutations(lists),
			addNewTask(state, payload) {
				const { boardListId, ...args } = convertKeysToCamelCase(payload);
				state[boardListId] = [
					{
						...args,
					},
					...(state[boardListId] || []),
				];
			},
			removeTask(state, payload) {
				const { boardListId, id } = convertKeysToCamelCase(payload);
				state[boardListId] = [
					...state[boardListId].filter((card) => card.id !== id),
				];
			},
			removeAllTasks(state, payload) {
				const { boardListId, ids } = convertKeysToCamelCase(payload);
				state[boardListId] = [
					...state[boardListId].filter((card) => !ids.includes(card.id)),
				];
			},
		},
		getters: {
			...generateGetters(lists),
		},
	}
};
