import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	deleteEvent,
	createEvent,
	updateEvent,
} from '../services/events';

const modules = [
	{ deleteEvent },
	{ createEvent },
	{ updateEvent },
];

export default {
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module),
		}), {}),
	},
}
