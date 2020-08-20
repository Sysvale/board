import http from '../../../http';

export const createCard = (payload) => http.post('/cards', payload);

export const getCardsByListsIds = (params) => http.get('/get-cards-by-lists-ids', {
	params,
});

export const updateCard = ({ id, ...params }) => http.put(`/cards/${id}`, params);

export const deleteCard = (id) => http.delete(`/cards/${id}`);

// Update card and his positions
export const updateCards = (cards = []) => new Promise((resolve, reject) => {
	setTimeout(() => {
		console.log('updateCards', '(positions updated)');
		resolve(cards);
	}, 1000);
});

// Update card and his positions when is a list change
export const updateCardsLists = ({ listOne, listTwo }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		console.log('updateCardsLists', '(the card list changed)');
		resolve({data:{ listOne, listTwo} });
	}, 1000);
});
