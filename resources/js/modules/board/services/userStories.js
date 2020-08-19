import generateUUID from '../../../core/utils/generateUUID';

const id = () => generateUUID().split('-')[0];

export const getUserStoriesByTeam = (teamId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: `${id()}-${teamId}`,
				title: 'Para fazer alguma coisa, eu como usuário, gostaria de fazer alguma coisa',
				estimated: 1,
				acceptanceCriteria: [
					'C1',
					'C2',
					'C3',
					'C4',
				],
			},
			{
				id: `${id()}-${teamId}`,
				title: 'Para fazer alguma coisa, eu como usuário, gostaria de fazer alguma coisa',
				estimated: 21,
				acceptanceCriteria: [
					'C1',
					'C2',
					'C3',
					'C4',
				],
			},
		];
		resolve({data});
	}, 1000);
});
