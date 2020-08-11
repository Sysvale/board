import Vue from 'vue';
import Vuex from 'vuex';

import makeRequestStore from '../../../core/utils/makeRequestStore';

import sprint from './sprint';
import userStories from './userStories';
import dev from './dev';
import notPlanned from './notPlanned';

import {
	getLists,
	getCardsByListsIds,
} from '../services';

const modules = [
	{ getLists },
	{ getCardsByListsIds },
];

Vue.use(Vuex);

export default new Vuex.Store({
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module, true),
		}), {
			sprint,
			userStories,
			dev,
			notPlanned,
		}),
	},
});
