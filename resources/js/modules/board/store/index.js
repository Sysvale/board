import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import userStories from './userStories';
import cards from './cards';
import members from './members';
import labels from './labels';

export default new Vuex.Store({
	namespaced: true,
	modules: {
		userStories,
		cards,
		members,
		labels,
	}
});
