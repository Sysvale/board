import { PLANNING_BOARD } from '../constants/defaultBoards';
import upperCamelCase from '../../../core/utils/upperCamelCase';
import generateUUID from '../../../core/utils/generateUUID';

const initialState = () => {
	let state = {};
	PLANNING_BOARD.forEach(({ key }, index) => {
		state = {
			...state,
			[key]: [],
		}
	});
	return state;
}

const generateMutations = () => {
	let mutations = {};
	PLANNING_BOARD.forEach(({ key }) => {
		let mutationName = `set${upperCamelCase(key)}`;
		mutations = {
			[mutationName]: (state, payload) => {
				state[key] = payload;
			},
			...mutations,
		};
	});
	return mutations;
}

const generateGetters = () => {
	let getters = {};
	PLANNING_BOARD.forEach(({ key }) => {
		let getterName = `get${upperCamelCase(key)}`;
		getters = {
			[getterName]: (state) => {
				return state[key];
			},
			...getters,
		};
	});
	return getters;
}


export default {
	namespaced: true,
	state: {
		...initialState(),
	},
	mutations: {
		...generateMutations(),
		addNewTask(state, payload) {
			const { listId, title } = payload;
			let xItems = [...state[listId]];
			xItems = [
				{
					id: generateUUID(),
					title,
				},
				...xItems,
			];
			state[listId] = [...xItems];
		},
		removeTask(state, payload) {
			const { listId, id} = payload;
			let xItems = [
				...state[listId].filter((card) => card.id !== id)
			];
			state[listId] = [...xItems];
		},
	},
	getters: {
		...generateGetters(),
	}
}
