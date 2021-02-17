import http from '../../../http';

export const getWorkspaces = () => new Promise((resolve, reject) => {
	setTimeout(() => {
		resolve({data: [
      {
        id: 1,
        name: 'Produto',
        image: 'https://assets5.lottiefiles.com/packages/lf20_jqc1jg5y.json',
        settings: {},
      },
      {
        id: 2,
        image: 'https://assets6.lottiefiles.com/packages/lf20_ntigg4tl.json',
        name: 'Digestão',
        settings: {
          noPlanningProblems: true,
        },
      },
    ]});
	}, 1000);
});
