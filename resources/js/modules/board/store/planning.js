import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getPlanningLists,
} from '../services/planning';

const modules = [
	{ getPlanningLists },
];

export default {
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module),
		}), {}),
	},
};
