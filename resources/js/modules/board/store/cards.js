import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	deleteCard,
	createCard,
	updateCard,
	updateCards,
	updateCardsLists,
} from '../services/cards';

const modules = [
	{ deleteCard },
	{ createCard },
	{ updateCard },
	{ updateCards },
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
