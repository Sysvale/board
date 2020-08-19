import generateUUID from '../../../core/utils/generateUUID';

const id = () => generateUUID().split('-')[0];

export const getTeams = () => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: id(),
				name: 'The Avengers',
			},
			{
				id: id(),
				name: 'Stepper Labs',
			},
		];
		resolve({ data });
	}, 1000);
})
