import Vue from 'vue';
import VueRouter from 'vue-router';
import draggable from 'vuedraggable';
import Cuida from '@sysvale/cuida';
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import {
	ValidationProvider,
	ValidationObserver,
	extend,
	setInteractionMode,
} from 'vee-validate';
// Import rules
import * as rules from 'vee-validate/dist/rules';

import validateLocale from 'vee-validate/dist/locale/pt_BR.json';
import App from './views/App.vue';
import vuetify from '../../vuetify';
import routes from './routes';
import store from './store';

setInteractionMode('lazy'); // Mode interation validation fields;
require('../../bootstrap');

for (let rule in rules) {
	extend(rule, {
		...rules[rule], // add the rule
		message: validateLocale.messages[rule] // add its message
	});
}

Vue.use(VueRouter);
Vue.use(Cuida);

// Routes
const router = new VueRouter({
	mode: 'history',
	routes,
});

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('Draggable', draggable);

const app = new Vue({
	render: (h) => h(App),
	vuetify,
	router,
	store,
}).$mount('#app');
