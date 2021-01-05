import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import cards from './cards';
import members from './members';
import labels from './labels';
import teams from './teams';
import boards from './boards'
import issues from './issues';
import sprint from './sprint';
import events from './events';

export default new Vuex.Store({
	namespaced: true,
	modules: {
		cards,
		members,
		labels,
		teams,
		boards,
		issues,
		sprint,
		events,
	}
});
