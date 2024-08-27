export const routes = {
	INDEX_ENTITIES: {
		label: 'Entidade',
		name: 'index-entities',
		path: 'index/entities',
		components: {
			default: () => import('../pages/EntitiesPage.vue')
		},
		meta: {
			description: 'Entidades',
		},
	},
};

export default routes;