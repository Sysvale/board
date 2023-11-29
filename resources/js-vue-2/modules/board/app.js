require('../../bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';
import vuetify from '../../vuetify';
import App from './views/App.vue';
import draggable from 'vuedraggable';
import {
	ValidationProvider,
	ValidationObserver,
	extend,
	setInteractionMode,
} from 'vee-validate';
import moment from 'moment';

import validateLocale from 'vee-validate/dist/locale/pt_BR.json';
import routes from './routes';
import store from './store';

setInteractionMode('lazy'); //Mode interation validation fields;

//Import rules
import * as rules from 'vee-validate/dist/rules';

for (let rule in rules) {
	extend(rule, {
    ...rules[rule], // add the rule
    message: validateLocale.messages[rule] // add its message
  });
}

Vue.use(VueRouter);

// Routes
const router = new VueRouter({
	mode: 'history',
	routes,
});

// emite evento customizado para capturar mudanças de rota sem depender do Vue
router.afterEach((to, from) => {
	const event = new CustomEvent('routeChange', {
		detail: {
			to,
			from,
		},
	});

	window.dispatchEvent(event);
});

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('Draggable', draggable);

Vue.prototype.$isChristmasSeason = (function isChristmasSeason() {
	const today = moment();
	const currentYear = today.year();
	const christmasSeasonStartDate = moment(`${currentYear}-12-10`);
	const christmasSeasonEndDate = moment(`${+currentYear + 1}-01-02`);

	return today.isBetween(christmasSeasonStartDate, christmasSeasonEndDate);
}());

const app = new Vue({
	render: (h) => h(App),
	vuetify,
	router,
	store,
}).$mount('#app-vue-2');
