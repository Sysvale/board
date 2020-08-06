require('../../bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import App from './views/App.vue';
import {
	ValidationProvider,
	ValidationObserver,
	extend,
	setInteractionMode,
} from 'vee-validate';

import validateLocale from 'vee-validate/dist/locale/pt_BR.json';
import routes from './routes';

//Import global COMPONENTS
import BTextInputWithValidation from '../../components/BTextInputWithValidation';
import BRadioBoxWithValidation from '../../components/BRadioBoxWithValidation';

setInteractionMode('lazy'); //Mode interation validation fields;

//Import rules
import * as rules from 'vee-validate/dist/rules';

for (let rule in rules) {
	extend(rule, {
    ...rules[rule], // add the rule
    message: validateLocale.messages[rule] // add its message
  });
}

Vue.use(BootstrapVue)
Vue.use(VueRouter);

// Routes
const router = new VueRouter({
	routes,
});

/*----------------------------------------------------------
--  GLOBAL COMPONENTS
-----------------------------------------------------------*/

Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('BTextInputWithValidation', BTextInputWithValidation);
Vue.component('BRadioBoxWithValidation', BRadioBoxWithValidation);


const app = new Vue({
	render: (h) => h(App),
	router,
}).$mount('#app');
