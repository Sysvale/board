import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	deleteCard,
	createCard,
	updateCard,
	updateCardsPositions,
	updateCardsLists,
} from '../services/cards';

const modules = [
	{ deleteCard },
	{ createCard },
	{ updateCard },
	{ updateCardsPositions },
	{ updateCardsLists },
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
