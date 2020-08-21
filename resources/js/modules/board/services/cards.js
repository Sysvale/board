import http from '../../../http';

export const createCard = (payload) => http.post('/cards', payload);

export const getCardsByListsIds = (params) => http.get('/get-cards-by-lists-ids', {
	params,
});

export const updateCard = ({ id, ...params }) => http.put(`/cards/${id}`, params);

export const deleteCard = (id) => http.delete(`/cards/${id}`);

export const updateCardsPositions = (cards = []) => http.post('/update-cards-positions', { cards });
