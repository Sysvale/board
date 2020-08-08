import Vue from 'vue';
import Vuex from 'vuex';

import makeRequestStore from '../../../core/utils/makeRequestStore';

import planning from './planning';
import sprint from './sprint';
import userStories from './userStories';
import dev from './dev';
import notPlanned from './notPlanned';

const modules = [];

Vue.use(Vuex);

export default new Vuex.Store({
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module, true),
		}), {
			planning,
			sprint,
			userStories,
			dev,
			notPlanned,
		}),
	},
});
