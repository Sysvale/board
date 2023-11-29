import Home from '../views/Home.vue';
import Create from '../views/Create.vue';
import Edit from '../views/Edit.vue';

export default [
	{
		path: '/process',
		name: 'processes',
		component: Home,
		meta: {
			title: 'Central de Processos',
		},
	},
	{
		path: '/process/edit/:id',
		name: 'processes.edit',
		component: Edit,
		meta: {
			title: 'Editar Processo',
		},
		props: true,
	},
	{
		path: '/process/new',
		name: 'processes.new',
		component: Create,
		meta: {
			title: 'Novo Processo',
		},
	},
];
