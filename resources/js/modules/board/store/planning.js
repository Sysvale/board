import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getPlanningLists,
	getCompanyPlanningLists,
} from '../services/planning';

const modules = [
	{ getPlanningLists },
	{ getCompanyPlanningLists },
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
