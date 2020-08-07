import Vue from 'vue';
import Vuex from 'vuex';

import planning from './planning';

console.log(planning);

Vue.use(Vuex);

export default new Vuex.Store({
	namespaced: true,
	modules: {
		planning,
	},
});
