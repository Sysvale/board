import { TASK_BOARD } from '../constants/defaultBoards';
import upperCamelCase from '../../../core/utils/upperCamelCase';

const initialState = () => {
	let state = {};
	TASK_BOARD.forEach(({ key }, index) => {
		state = {
			...state,
			[key]: [],
		}
	});
	return state;
}

const generateMutations = () => {
	let mutations = {};
	TASK_BOARD.forEach(({ key }) => {
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
	TASK_BOARD.forEach(({ key }) => {
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
	},
	getters: {
		...generateGetters(),
	}
}
