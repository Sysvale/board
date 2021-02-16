import http from '../../../http';

export const getWorkspaces = () => new Promise((resolve, reject) => {
	setTimeout(() => {
		resolve({data: [
      {
        id: 1,
        name: 'Produto',
        settings: {},
      },
      {
        id: 2,
        name: 'Digestão',
        settings: {
          noPlanningProblems: true,
        },
      },
      {
        id: 3,
        name: 'Data itim',
        settings: {},
      },
    ]});
	}, 1000);
});
