import Vue from 'vue';
import Vuex from 'vuex';

import cards from './cards';
import labels from './labels';
import teams from './teams';
import boards from './boards';
import issues from './issues';
import sprint from './sprint';
import events from './events';
import goals from './goals';
import planning from './planning';

import settingsModules from '../../settings/store';
import processesModules from '../../processes/store';

Vue.use(Vuex);

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
		goals,
		planning,
		...settingsModules,
		...processesModules,
	},
});
