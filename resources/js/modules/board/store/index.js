import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import cards from './cards';
import members from './members';
import labels from './labels';
import impediments from './impediments';
import teams from './teams';

export default new Vuex.Store({
	namespaced: true,
	modules: {
		cards,
		members,
		labels,
		impediments,
		teams,
	}
});
