import Home from '../views/Home.vue';
import SprintReport from '../views/SprintReport.vue';

export default [
	{
		path: '/reports',
		name: 'reports',
		component: Home,
		meta: {
			title: 'Relatórios',
		},
	},
	{
		path: '/reports/sprints',
		name: 'reports.sprint',
		component: SprintReport,
		meta: {
			title: 'Relatórios de Sprint',
		},
	},
];
