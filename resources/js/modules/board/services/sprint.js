import generateUUID from '../../../core/utils/generateUUID';

const id = () => generateUUID().split('-')[0];

const title = () => {
	const s = `A população ela precisa da Zona Franca de Manaus, porque na Zona franca de Manaus, não é uma zona de exportação, é uma zona para o Brasil. Portanto ela tem um objetivo, ela evita o desmatamento, que é altamente lucravito. Derrubar arvores da natureza é muito lucrativo!Eu dou dinheiro pra minha filha. Eu dou dinheiro pra ela viajar, então é... é... Já vivi muito sem dinheiro, já vivi muito com dinheiro. -Jornalista: Coloca esse dinheiro na poupança que a senhora ganha R$10 mil por mês. -Dilma: O que que é R$10 mil?Primeiro eu queria cumprimentar os internautas. -Oi Internautas! Depois dizer que o meio ambiente é sem dúvida nenhuma uma ameaça ao desenvolvimento sustentável. E isso significa que é uma ameaça pro futuro do nosso planeta e dos nossos países. O desemprego beira 20%, ou seja, 1 em cada 4 portugueses.Ai você fala o seguinte: "- Mas vocês acabaram isso?" Vou te falar: -"Não, está em andamento!" Tem obras que "vai" durar pra depois de 2010. Agora, por isso, nós já não desenhamos, não começamos a fazer projeto do que nós "podêmo fazê"? 11, 12, 13, 14... Por que é que não?Se hoje é o dia das crianças... Ontem eu disse: o dia da criança é o dia da mãe, dos pais, das professoras, mas também é o dia dos animais, sempre que você olha uma criança, há sempre uma figura oculta, que é um cachorro atrás. O que é algo muito importante!A única área que eu acho, que vai exigir muita atenção nossa, e aí eu já aventei a hipótese de até criar um ministério. É na área de... Na área... Eu diria assim, como uma espécie de analogia com o que acontece na área agrícola.No meu xinélo da humildade eu gostaria muito de ver o Neymar e o Ganso. Por que eu acho que.... 11 entre 10 brasileiros gostariam. Você veja, eu já vi, parei de ver. Voltei a ver, e acho que o Neymar e o Ganso têm essa capacidade de fazer a gente olhar.Todos as descrições das pessoas são sobre a humanidade do atendimento, a pessoa pega no pulso, examina, olha com carinho. Então eu acho que vai ter outra coisa, que os médicos cubanos trouxeram pro brasil, um alto grau de humanidade.`;
	let splited = s.split('.')
	let index = Math.floor(Math.random() * (splited.length - 0) + 0);
	return splited[index] || "cachorro? sou cachorro não";
}

export const getDefaultLists = () => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: id(),
				name: 'To Do',
				position: 1,
			},
			{
				id: id(),
				name: 'Em desenvolvimento',
				position: 2,
			},
			{
				id: id(),
				name: 'Code Review/Test',
				position: 3,
			},
			{
				id: id(),
				name: 'Done/To Release',
				position: 4,
			},
			{
				id: id(),
				name: 'Deploy',
				position: 5,
			},
		];
		resolve({ data });
	})
});

export const getSprintDevlogLists = () => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: id(),
				name: 'Devlog',
				position: 1,
			},
			{
				id: id(),
				name: 'To Do',
				position: 1,
			},
			{
				id: id(),
				name: 'Em desenvolvimento',
				position: 2,
			},
			{
				id: id(),
				name: 'Code Review/Test',
				position: 3,
			},
			{
				id: id(),
				name: 'Done/To Release',
				position: 4,
			},
			{
				id: id(),
				name: 'Deploy',
				position: 5,
			},
		];
		resolve({ data });
	})
});

export const getSprintDevlogTasksByTeam = ({ listsIds, teamId }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let computed = {};
		listsIds.forEach((listId) => {
			computed = {
				[listId]: [
					{
						id: id(),
						number: Math.floor(Math.random() * (999 - 1) + 1),
						title: title(),
						link: 'https://gitlab.com/syssus/server/-/merge_requests/2311',
						labels: [
							{
								id: id(),
								name: 'BackEnd',
								color: '#222',
								textColor: 'white',
							},
						],
						members: [
							{
								id: id(),
								name: 'Tássio Caique',
							},
							{
								id: id(),
								name: 'Rafa Dias',
							},
						]
					},
					{
						id: id(),
						number: Math.floor(Math.random() * (999 - 1) + 1),
						title: title(),
					},
				],
				...computed,
			}
		});
		const data = {
			...computed,
		};
		resolve({data});
	}, 1000);
});


export const getNotPlannedTasksByTeam = ({ listsIds, teamId }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let computed = {};
		listsIds.forEach((listId) => {
			computed = {
				[listId]: [
					{
						id: id(),
						number: Math.floor(Math.random() * (999 - 1) + 1),
						title: title(),
						link: 'https://gitlab.com/syssus/server/-/merge_requests/2311',
						members: [
							{
								id: id(),
								name: 'Tássio Caique',
							},
						]
					},
					{
						id: id(),
						number: Math.floor(Math.random() * (999 - 1) + 1),
						title: title(),
					},
				],
				...computed,
			}
		});
		const data = {
			...computed,
		};
		resolve({data});
	}, 1000);
});
