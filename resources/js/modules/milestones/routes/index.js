import Home from '../views/Home.vue';
import Board from '../views/Board.vue';

export default [
	{
		path: '/milestone',
		name: 'milestones',
		component: Home,
		meta: {
			title: 'Milestones',
		},
	},
	{
		path: '/milestones/:id/board',
		name: 'milestones.board',
		component: Board,
		meta: {
			title: 'Milestones',
		},
		props: true,
	},
];
