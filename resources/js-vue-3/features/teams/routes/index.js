export const routes = {
	INDEX_TEAMS: {
		label: 'Times',
		name: 'index-teams',
		path: 'index/teams',
		components: {
			default: () => import('../pages/TeamsPage.vue')
		},
		meta: {
			description: 'Times',
		},
	},
};

export default routes;