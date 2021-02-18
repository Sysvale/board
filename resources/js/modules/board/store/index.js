import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import cards from './cards';
import labels from './labels';
import teams from './teams';
import boards from './boards'
import issues from './issues';
import sprint from './sprint';
import events from './events';

import settingsModules from '../../settings/store';

export default new Vuex.Store({
	namespaced: true,
	modules: {
		cards,
		labels,
		teams,
		boards,
		issues,
		sprint,
		events,
		...settingsModules,
	}
});
