import makeRequestStore from '../../../core/utils/makeRequestStore';
import generateUUID from '../../../core/utils/generateUUID';

import {
	deleteCard,
	createCard,
	updateCard,
	updateCards,
} from '../services';

const modules = [
	{ deleteCard },
	{ createCard },
	{ updateCard },
	{ updateCards },
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
