export const routes = {
	INDEX_WORKSPACES: {
		label: 'Entidade',
		name: 'index-workspaces',
		path: 'index/workspaces',
		components: {
			default: () => import('../pages/WorkspacesPage.vue')
		},
		meta: {
			description: 'Entidades',
		},
	},
};

export default routes;