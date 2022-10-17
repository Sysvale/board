import Home from '../views/Home.vue';
import Members from '../views/Members.vue';
import Workspaces from '../views/Workspaces.vue';
import Teams from '../views/Teams.vue';
import Labels from '../views/Labels.vue';

export default [
	{
		path: '/settings/workspaces',
		name: 'settings.workspaces',
		component: Workspaces,
		meta: {
			title: 'Workspaces',
		},
	},
	{
		path: '/settings/members',
		name: 'settings.members',
		component: Members,
		meta: {
			title: 'Membros',
		},
	},
	{
		path: '/settings/teams',
		name: 'settings.teams',
		component: Teams,
		meta: {
			title: 'Times',
		},
	},
	{
		path: '/settings/labels',
		name: 'settings.labels',
		component: Labels,
		meta: {
			title: 'Categorias',
		},
	},
	{
		path: '/settings',
		name: 'settings',
		component: Home,
		meta: {
			title: 'Configurações',
		},
	},
];
